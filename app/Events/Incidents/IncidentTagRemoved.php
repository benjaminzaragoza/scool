<?php

namespace App\Events\Incidents;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class IncidentTagRemoved.
 *
 * @package App\Events\Incidents
 */
class IncidentTagRemoved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident;

    public $oldTag;

    /**
     * IncidentTagRemoved constructor.
     * @param $incident
     * @param $oldTag
     */
    public function __construct($incident, $oldTag)
    {
        $this->incident = $incident;
        $this->oldTag = $oldTag;
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
