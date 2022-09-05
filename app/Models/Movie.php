<?php

namespace App\Models;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getTotalLikesDislikes($value)
    {
        return $this->users()->where('like', $value)->count();
    }

    public function getCurrentUserLikes()
    {
        return $this->users()->where('user_id', Auth::id())->first()?->pivot->like;
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['like']);
    }

    public function loadData()
    {
        $this->load(['category']);
        $this['like'] = $this->getCurrentUserLikes();
        $this['total_likes'] = $this->getTotalLikesDislikes(true);
        $this['total_dislikes'] = $this->getTotalLikesDislikes(false);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
