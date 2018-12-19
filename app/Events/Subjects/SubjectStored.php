<?php

namespace App\Events\Subjects;

use App\Models\Subject;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class SubjectStored.
 *
 * @package App\Events\Subjects
 */
class SubjectStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subject;

    /**
     * SubjectStored constructor.
     * @param Subject $subject
     * @param $oldSubject
     *
     */
    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
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
