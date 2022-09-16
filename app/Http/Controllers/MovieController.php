<?php

namespace App\Http\Controllers;

use App\Events\LikeUpdate;
use App\Events\MovieCreatedEvent;
use App\Models\Movie;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Image as ImageModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $category = $request->input('category');
        $search = $request->input('search');
        $myMoviesList = $request->input('my_movies');

        $movies = Movie::with('category');

        if ($category) {
            $movies->where('category_id', '=', $category);
        }

        if ($search) {
            $movies->where('title', 'like', "%{$search}%");
        }

        if ($myMoviesList === 'true') {
            $user_movies = $user->getUserWatchList();
            $movies->whereIn('id', $user_movies);
        }

        $movies = $movies->latest()->paginate(10);


        foreach ($movies as $movie) {
            $movie->loadData();
            if (isset($movie->image->thumbnail)) {
                $imageURL = Storage::url(env('IMAGE_THUMBNAIL_FOLDER') . '/' . $movie->image->thumbnail);
                $movie['thumbnail'] = $imageURL;
            }
        }

        return response()->json($movies);
    }

    public function showTopMovies(Request $request)
    {
        $movies = Movie::getTopMovies();

        return response()->json($movies);
    }

    public function showRelatedMovies(Request $request)
    {
        $category = $request->input('category');

        $movies = Movie::where('category_id', $category)->inRandomOrder()->limit(10)->get();

        return response()->json($movies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMovieRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMovieRequest $request)
    {

        $data = $request->validated();

        //Create movie
        $movie = Movie::create($data);

        //Get image from request
        $image = $data['image'];

        //Resize image
        $imageFullSize = Image::make($image->getRealPath());
        $imageFullSize->resize(400, 400, function ($constraint) {
            $constraint->aspectRatio();
        });
        $imageThumbnail = Image::make($image->getRealPath());
        $imageThumbnail->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();
        });

        //Set images path & name
        $imageFullSizePath = public_path("storage\\" . env('IMAGE_FULL_SIZE_FOLDER'));
        $imageThumbnailPath = public_path("storage\\" . env('IMAGE_THUMBNAIL_FOLDER'));
        $imageFullSizeName = $movie->id . '-full_size.' . $image->extension();
        $imageThumbnailName = $movie->id . '-thumbnail.' . $image->extension();

        //Store to disk
        $imageFullSize->save($imageFullSizePath . "\\" . $imageFullSizeName);
        $imageThumbnail->save($imageThumbnailPath . "\\" . $imageThumbnailName);

        //Add images data to table
        ImageModel::updateOrCreate(
            ['movie_id' => $movie->id],
            [
                'full_size' => $imageFullSizeName,
                'thumbnail' => $imageThumbnailName
            ]
        );

        return response()->json($movie);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        $movie->increment('visited');

        $movie->loadData();
        $comments = $movie->comments()->with('user')->latest()->paginate(3);

        if (isset($movie->image->full_size)) {
            $imageURL = Storage::url(env('IMAGE_FULL_SIZE_FOLDER') . '/' . $movie->image->full_size);
            $movie['full_size'] = $imageURL;
        };

        return response()->json(['movie' => $movie, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMovieRequest  $request
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMovieRequest $request, Movie $movie)
    {
        $like = $request->only('like');
        $watched = $request->only('watched');

        if ($like) {
            $likedAlready = $movie->users()
                ->where('user_id',  Auth::id())
                ->where('like', 1)
                ->first();
            if ($likedAlready) {
                throw new \Exception('Cannot like multiple times');
            }
            $movie->users()->syncWithoutDetaching([Auth::id() => $like]);
        }
        if ($watched) {
            $movie->watches()->syncWithoutDetaching([Auth::id() => $watched]);
        }

        $movie->loadData();

        LikeUpdate::dispatch($movie);

        return response()->json($movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response($movie);
    }
}
