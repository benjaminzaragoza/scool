<?php

namespace App\Listeners\Incidents;

use App;
use App\Jobs\LogIncidentEvent;

/**
 * Class LogIncidentTagAdded
 * @package App\Listeners\Incidents
 */
class LogIncidentTagAdded
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
        LogIncidentEvent::dispatch('tagAdded',$event)->onQueue(tenant_from_current_url());
    }
}
