<?php

namespace App\Listeners\Incidents;

use App\Mail\Incidents\IncidentDeleted;
use App\Mail\Incidents\IncidentSubjectModified;
use App\Models\Setting;
use Mail;

/**
 * Class SendIncidentSubjectUpdateEmail.
 *
 * @package App\Listeners\Incidents
 */
class SendIncidentSubjectUpdateEmail
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
            ->queue((new IncidentSubjectModified($event->incident))->onQueue(tenant_from_current_url()));
    }
}
