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
 * Class IncidentDescriptionUpdated
 * @package App\Events\Incidents
 */
class IncidentDescriptionUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident;

    public $oldIncident;

    /**
     * IncidentDescriptionUpdated constructor.
     * @param Incident $incident
     * @param $oldIncident
     *
     */
    public function __construct(Incident $incident,$oldIncident)
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
