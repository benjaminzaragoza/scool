<?php

namespace App\Jobs;

use App\Listeners\Incidents\IncidentLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class LogIncidentEvent.
 *
 * @package App\Jobs
 */
class LogIncidentEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $action;
    public $event;

    /**
     * Log constructor.
     * @param $event
     * @param $action
     */
    public function __construct($action, $event)
    {
        $this->action = $action;
        $this->event = $event;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        IncidentLogger::{$this->action}($this->event);
    }
}
