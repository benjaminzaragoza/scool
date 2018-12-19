<?php

namespace App\Events\Subjects;

use App\Models\Subject;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class SubjectDeleted.
 *
 * @package App\Events\Subjects
 */
class SubjectDeleted
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
    public function __construct(Subject $oldSubject)
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
