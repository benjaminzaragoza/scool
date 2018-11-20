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
 * Class IncidentDeleted
 * @package App\Events\Incidents
 */
class IncidentDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oldIncident;

    /**
     * IncidentDeleted constructor.
     *
     * @param $oldIncident
     */
    public function __construct($oldIncident)
    {
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
