<?php

namespace App\Events\Positions;

use App\Models\Position;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class PositionStored.
 *
 * @package App\Events\Positions
 */
class PositionStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $position;

    /**
     * PositionStored constructor.
     * @param Position $position
     * @param $oldPosition
     *
     */
    public function __construct(Position $position)
    {
        $this->position = $position;
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
