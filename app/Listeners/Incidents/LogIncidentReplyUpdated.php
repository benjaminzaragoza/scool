<?php

namespace App\Listeners\Incidents;

use App;
use App\Jobs\LogIncidentEvent;

/**
 * Class LogIncidentReplyUpdated
 * @package App\Listeners\Incidents
 */
class LogIncidentReplyUpdated
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
        LogIncidentEvent::dispatch('replyUpdated',$event)->onQueue(tenant_from_current_url());
    }
}
