<?php

namespace App\Events\Studies;

use App\Models\Department;
use App\Models\Study;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class StudyDepartmentUpdated
 * @package App\Events\Studies
 */
class StudyDepartmentUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $study;

    public $department;

    /**
     * StudyDepartmentUpdated constructor.
     * @param Study $study
     * @param $department
     *
     */
    public function __construct(Study $study,Department $department)
    {
        $this->study = $study;
        $this->department = $department;
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
