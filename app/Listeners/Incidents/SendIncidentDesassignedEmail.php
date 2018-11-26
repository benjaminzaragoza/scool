<?php

namespace App\Listeners\Incidents;

use App\Mail\Incidents\IncidentCommentAdded;
use App\Mail\Incidents\IncidentDesassigned;
use App\Mail\Incidents\IncidentUntagged;
use App\Models\Setting;
use Mail;

/**
 * Class SendIncidentUnassignedEmail.
 *
 * @package App\Listeners\Incidents
 */
class SendIncidentDesassignedEmail
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
        Mail::to($event->incident->user)->cc(Setting::get('incidents_manager_email'))->queue(new IncidentDesassigned($event->incident));
    }
}
