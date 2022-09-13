<?php

namespace App\Jobs;

use App\Events\MovieCreatedEvent;
use App\Mail\MovieCreated;
use App\Models\Movie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Test retrying of a single failed job
    public $tries = 3;
    public $backoff = 3;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public MovieCreatedEvent $event)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Mail::send(new MovieCreated($this->event->movie));
        throw new \Exception('Job failed');
    }

    public function failed(Throwable $exception)
    {
    }

    // Test retrying all of the failed jobs
    // php artisan queue:retry all

    // Test permanently deleting failed jobs
    // php artisan queue:flush
}
