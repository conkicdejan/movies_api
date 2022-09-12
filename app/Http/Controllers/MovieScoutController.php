<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class MovieScoutController extends Controller
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

        $movies = Movie::search($search)
            ->query(function ($query) use ($category, $myMoviesList, $user) {
                if ($category)
                    $query->where('category_id', '=', $category);
                if ($myMoviesList === 'true') {
                    $user_movies = $user->getUserWatchList();
                    $query->whereIn('id', $user_movies);
                }
            })
            ->orderBy('created_at')
            ->paginate(10);

        foreach ($movies as $movie) {
            $movie->loadData();
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

        $movie = Movie::create($data);

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
            $movie->users()->syncWithoutDetaching([Auth::id() => $like]);
        }
        if ($watched) {
            $movie->watches()->syncWithoutDetaching([Auth::id() => $watched]);
        }

        $movie->loadData();
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
