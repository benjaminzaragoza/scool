<?php

namespace App\Events\Positions;

use App\Models\Position;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class PositionCodeUpdated
 * @package App\Events\Positions
 */
class PositionCodeUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $position;

    public $oldPosition;

    /**
     * PositionCodeUpdated constructor.
     * @param Position $position
     * @param $oldPosition
     *
     */
    public function __construct(Position $position,$oldPosition)
    {
        $this->position = $position;
        $this->oldPosition = $oldPosition;
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
