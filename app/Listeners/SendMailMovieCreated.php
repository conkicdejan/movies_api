<?php

namespace App\Listeners;

use App\Events\MovieCreatedEvent;
use App\Mail\MovieCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailMovieCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\MovieCreatedEvent  $event
     * @return void
     */
    public function handle(MovieCreatedEvent $event)
    {
        
        Mail::send(new MovieCreated($event->movie));
    }
}
