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
 * Class IncidentReplyRemoved.
 *
 * @package App\Events\Incidents
 */
class IncidentReplyRemoved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident;

    public $reply;

    /**
     * IncidentReplyRemoved constructor.
     * @param $incident
     * @param $reply
     */
    public function __construct($incident, $reply)
    {
        $this->incident = $incident;
        $this->reply = $reply;
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
