<?php

namespace App\Events\SubjectGroups;

use App\Models\SubjectGroup;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class SubjectGroupSubjectsNumberUpdated.
 *
 * @package App\Events\Studies
 */
class SubjectGroupSubjectsNumberUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subjectGroup;

    public $oldSubjectGroup;

    /**
     * SubjectGroupSubjectsNumberUpdated.
     * constructor.
     * @param SubjectGroup $subjectGroup
     * @param $oldSubjectGroup
     *
     */
    public function __construct(SubjectGroup $subjectGroup,$oldSubjectGroup)
    {
        $this->subjectGroup = $subjectGroup;
        $this->oldSubjectGroup = $oldSubjectGroup;
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
