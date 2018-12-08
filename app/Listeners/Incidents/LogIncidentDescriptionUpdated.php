<?php

namespace App\Listeners\Incidents;

use App;
use App\Jobs\LogIncidentEvent;

/**
 * Class LogIncidentDescriptionUpdated
 * @package App\Listeners\Incidents
 */
class LogIncidentDescriptionUpdated
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
        LogIncidentEvent::dispatch('descriptionUpdated',$event)->onQueue(tenant_from_current_url());
    }
}
