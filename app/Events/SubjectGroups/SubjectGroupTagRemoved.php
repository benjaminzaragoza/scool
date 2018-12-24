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
 * Class SubjectGroupTagRemoved.
 *
 * @package App\Events\Studies
 */
class SubjectGroupTagRemoved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subjectGroup;

    public $oldTag;

    /**
     * SubjectGroupTagRemoved constructor.
     * @param $subjectGroup
     * @param $oldTag
     */
    public function __construct($subjectGroup, $oldTag)
    {
        $this->subjectGroup = $subjectGroup;
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
