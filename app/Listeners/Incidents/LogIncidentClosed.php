<?php

namespace App\Listeners\Incidents;

use App;

/**
 * Class LogIncidentClosed
 * @package App\Listeners\Incidents
 */
class LogIncidentClosed
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
        IncidentLogger::closed($event);
    }
}
