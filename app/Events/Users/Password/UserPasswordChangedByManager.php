<?php

namespace App\Events\Users\Password;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class UserPasswordChangedByManager.
 *
 * @package App\Events\Users\Password
 */
class UserPasswordChangedByManager
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $options;

    /**
     * UserPasswordChangedByManager constructor.
     * @param $user
     * @param $options
     */
    public function __construct(User $user, array $options)
    {
        $this->user = $user;
        $this->options = $options;
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
