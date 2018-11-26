<?php

namespace App\Events\Incidents;

use App\Models\Incident;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class IncidentClosed.
 *
 * @package App\Events\Incidents
 */
class IncidentClosed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident;

    public $oldIncident;

    /**
     * IncidentClosed constructor.
     *
     * @param $incident
     * @param $oldIncident
     */
    public function __construct($incident, $oldIncident)
    {
        $this->incident = $incident;
        $this->oldIncident = $oldIncident;
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
