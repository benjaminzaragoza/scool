<?php

namespace App\Events\Studies;

use App\Models\Family;
use App\Models\Study;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class StudyFamilyUpdated
 * @package App\Events\Studies
 */
class StudyFamilyUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $study;

    public $family;

    /**
     * StudyFamilyUpdated constructor.
     * @param Study $study
     * @param $family
     *
     */
    public function __construct(Study $study,Family $family)
    {
        $this->study = $study;
        $this->family = $family;
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
