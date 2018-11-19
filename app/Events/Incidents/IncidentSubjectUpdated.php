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
 * Class IncidentSubjectUpdated
 * @package App\Events\Incidents
 */
class IncidentSubjectUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident;

    /**
     * IncidentSubjectUpdated constructor.
     * @param $incident
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
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
