<?php

namespace App\Events\Subjects;

use App\Models\Subject;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class SubjectNameUpdated
 * @package App\Events\Studies
 */
class SubjectNameUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subject;

    public $oldSubject;

    /**
     * SubjectNameUpdated constructor.
     * @param Subject $subject
     * @param $oldSubject
     *
     */
    public function __construct(Subject $subject,$oldSubject)
    {
        $this->subject = $subject;
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
