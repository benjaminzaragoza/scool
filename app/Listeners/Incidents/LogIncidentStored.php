<?php

namespace App\Listeners\Incidents;

use App;
use App\Jobs\LogIncidentEvent;

/**
 * Class LogIncidentStored
 *
 * @package App\Listeners\Incidents
 */
class LogIncidentStored
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (App::environment('testing')) return;
        LogIncidentEvent::dispatch('stored',$event)->onQueue(tenant_from_current_url());
    }
}
