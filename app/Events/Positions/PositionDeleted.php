<?php

namespace App\Events\Positions;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class PositionDeleted
 * @package App\Events\Positions
 */
class PositionDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oldPosition;

    /**
     * PositionDeleted constructor.
     *
     * @param $oldPosition
     */
    public function __construct($oldPosition)
    {
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
