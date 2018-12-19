<?php

namespace App\Events\Studies;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class StudyTagRemoved.
 *
 * @package App\Events\Studies
 */
class StudyTagRemoved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $study;

    public $oldTag;

    /**
     * StudyTagRemoved constructor.
     * @param $study
     * @param $oldTag
     */
    public function __construct($study, $oldTag)
    {
        $this->study = $study;
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
