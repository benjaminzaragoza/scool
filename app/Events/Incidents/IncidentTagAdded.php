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
 * Class IncidentTagAdded.
 *
 * @package App\Events\Incidents
 */
class IncidentTagAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident;

    public $tag;

    /**
     * IncidentTagAdded constructor.
     * @param $incident
     * @param $tag
     */
    public function __construct($incident, $tag)
    {
        $this->incident = $incident;
        $this->tag = $tag;
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
