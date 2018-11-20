<?php

namespace App\Listeners\Incidents;

use App;

/**
 * Class LogIncidentTagRemoved
 * @package App\Listeners\Incidents
 */
class LogIncidentTagRemoved
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (App::environment('testing')) return;
        IncidentLogger::tagRemoved($event);
    }
}
