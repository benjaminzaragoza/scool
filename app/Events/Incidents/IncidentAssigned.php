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
 * Class IncidentAssigned.
 *
 * @package App\Events\Incidents
 */
class IncidentAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident;

    public $user;

    /**
     * IncidentAssigned constructor.
     * @param $incident
     * @param $user
     */
    public function __construct($incident, $user)
    {
        $this->incident = $incident;
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
