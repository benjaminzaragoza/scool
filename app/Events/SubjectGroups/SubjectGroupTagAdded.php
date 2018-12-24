<?php

namespace App\Events\SubjectGroups;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class SubjectGroupTagAdded.
 *
 * @package App\Events\SubjectGroups
 */
class SubjectGroupTagAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subjectGroup;

    public $tag;

    /**
     * SubjectGroupTagAdded constructor.
     *
     * @param $subjectGroup
     * @param $tag
     */
    public function __construct($subjectGroup, $tag)
    {
        $this->subjectGroup = $subjectGroup;
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
