<?php

namespace App\Models;

use App\Events\MovieCreatedEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'category_id'
    ];

    protected $dispatchesEvents = [
        'created' => MovieCreatedEvent::class,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getTotalLikesDislikes($value)
    {
        return $this->users()->where('like', $value)->count();
    }

    public static function getTopMovies()
    {
        return $movies = DB::table('movie_user')
            ->leftjoin('movies', 'movie_user.movie_id', '=', 'movies.id')
            ->selectRaw('movie_user.movie_id as id, movies.title as title, count(movie_user.like) as likes')
            ->where('like', 1)
            ->groupBy('id')
            ->orderByDesc('likes')
            ->limit(10)
            ->get();
    }

    public function getCurrentUserLikes()
    {
        return $this->users()->where('user_id', Auth::id())->first()?->pivot->like;
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['like']);
    }

    public function watches()
    {
        return $this->belongsToMany(User::class, 'watch_lists')->withPivot(['watched']);
    }

    public function getCurrentUserWatched()
    {
        return $this->watches()->where('user_id', Auth::id())->first()?->pivot->watched;
    }

    public function loadData()
    {
        $this->load(['category']);
        $this['like'] = $this->getCurrentUserLikes();
        $this['total_likes'] = $this->getTotalLikesDislikes(true);
        $this['total_dislikes'] = $this->getTotalLikesDislikes(false);
        $this['watched'] = $this->getCurrentUserWatched();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
