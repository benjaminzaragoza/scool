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
 * Class MoodleUserAssociated
 *
 * @package App\Events\Incidents
 */
class MoodleUserAssociated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $moodleUser;

    /**
     * MoodleUserAssociated constructor.
     * @param $user
     * @param $moodleUser
     */
    public function __construct($user,$moodleUser)
    {
        $this->user = $user;
        $this->moodleUser = $moodleUser;
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
