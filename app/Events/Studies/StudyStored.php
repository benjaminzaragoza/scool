<?php

namespace App\Events\Studies;

use App\Models\Study;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class StudyStored.
 *
 * @package App\Events\Studies
 */
class StudyStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $study;

    /**
     * StudyStored constructor.
     * @param Study $study
     * @param $oldStudy
     *
     */
    public function __construct(Study $study)
    {
        $this->study = $study;
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
