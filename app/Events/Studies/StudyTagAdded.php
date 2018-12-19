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
 * Class StudyTagAdded.
 *
 * @package App\Events\Studies
 */
class StudyTagAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $study;

    public $tag;

    /**
     * StudyTagAdded constructor.
     *
     * @param $study
     * @param $tag
     */
    public function __construct($study, $tag)
    {
        $this->study = $study;
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
