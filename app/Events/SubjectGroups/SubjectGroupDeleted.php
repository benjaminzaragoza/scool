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

    public $oldSubject;

    /**
     * SubjectDeleted constructor.
     *
     * @param Subject $oldSubject
     * @param $oldSubject
     *
     */
    public function __construct($oldSubject)
    {
        $this->oldSubject = $oldSubject;
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
