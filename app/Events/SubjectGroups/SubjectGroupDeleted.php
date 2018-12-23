<?php

namespace App\Events\SubjectGroups;

use App\Models\Subject;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class SubjectGroupDeleted.
 *
 * @package App\Events\Subjects
 */
class SubjectGroupDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oldSubjectGroup;

    /**
     * SubjectDeleted constructor.
     *
     * @param Subject $oldSubjectGroup
     * @param $oldSubjectGroup
     *
     */
    public function __construct($oldSubjectGroup)
    {
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
