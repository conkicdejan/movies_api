<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class WatchList extends Model
{
    use HasFactory;

    // public $incrementing = true;

    protected $fillable = [
        'watched',
        'movie_id'
    ];
}
