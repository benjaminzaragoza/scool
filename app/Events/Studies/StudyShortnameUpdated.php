<?php

namespace App\Events\Studies;

use App\Models\Study;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class StudyShortnameUpdated
 * @package App\Events\Studies
 */
class StudyShortnameUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $study;

    public $oldStudy;

    /**
     * StudyShortnameUpdated constructor.
     * @param Study $study
     * @param $oldStudy
     *
     */
    public function __construct(Study $study,$oldStudy)
    {
        $this->study = $study;
        $this->oldStudy = $oldStudy;
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
