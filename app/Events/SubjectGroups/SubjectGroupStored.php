<?php

namespace App\Events\SubjectGroups;

use App\Models\SubjectGroup;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class SubjectGroupStored.
 *
 * @package App\Events\SubjectGroups
 */
class SubjectGroupStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subjectGroup;

    /**
     * SubjectGroupStored constructor.
     *
     * @param SubjectGroup $subjectGroup
     * @param $oldSubjectGroup
     *
     */
    public function __construct(SubjectGroup $subjectGroup)
    {
        $this->subjectGroup = $subjectGroup;
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
