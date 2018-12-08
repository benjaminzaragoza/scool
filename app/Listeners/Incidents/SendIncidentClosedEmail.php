<?php

namespace App\Listeners\Incidents;

use App\Mail\Incidents\IncidentClosed;
use App\Models\Setting;
use Mail;

/**
 * Class SendIncidentClosedEmail.
 *
 * @package App\Listeners\Incidents
 */
class SendIncidentClosedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->incident->user)->cc(Setting::get('incidents_manager_email'))
            ->queue((new IncidentClosed($event->incident))->onQueue(tenant_from_current_url()));
    }
}
