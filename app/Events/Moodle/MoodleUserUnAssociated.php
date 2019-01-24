<?php

namespace App\Events\Moodle;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class MoodleUserUnAssociated
 *
 * @package App\Events\Incidents
 */
class MoodleUserUnAssociated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    /**
     * MoodleUserAssociated constructor.
     * @param $user
     * @param $moodleUser
     */
    public function __construct($user)
    {
        $this->user = $user;
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
