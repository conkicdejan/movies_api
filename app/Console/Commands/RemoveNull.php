<?php

namespace App\Console\Commands;

use App\Models\Like;
use App\Models\WatchList;
use Illuminate\Console\Command;

class RemoveNull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:null';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove null values from movie_user (like) and watch_list tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        WatchList::where('watched', null)->delete();
        Like::where('like', null)->delete();
    }
}
