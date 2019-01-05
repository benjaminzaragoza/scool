<?php

namespace App\Events\Positions;

use App\Models\Position;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class PositionUserStored.
 *
 * @package App\Events\Positions
 */
class PositionUserStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $position;
    public $user;

    /**
     * PositionUserStored constructor.
     * @param Position $position
     * @param User $user
     */
    public function __construct(Position $position, User $user)
    {
        $this->position = $position;
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
