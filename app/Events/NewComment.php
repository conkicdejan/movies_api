<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\BroadcastableModelEventOccurred;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Comment $comment)
    {
        $this->dontBroadcastToCurrentUser();
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // info('broad','movie.' . $this->comment->movie->id);
        return new Channel('movie.' . $this->comment->movie->id);
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'content' => $this->comment->content,
    //         'created_at' => $this->comment->created_at,
    //         'user' =>
    //         [
    //             'name' => $this->commment->user->name
    //         ],
    //     ];
    // }
}
