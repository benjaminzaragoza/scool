<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class UserPhotoRemoved.
 *
 * @package App\Events
 */
class UserPhotoRemoved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $path;

    /**
     * UserPhotoRemoved constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
