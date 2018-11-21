<?php

namespace App\Listeners\Incidents;

use App\Mail\Incidents\IncidentAssigned;
use App\Mail\Incidents\IncidentCommentAdded;
use App\Mail\Incidents\IncidentUntagged;
use App\Models\Setting;
use Mail;

/**
 * Class SendIncidentAssignedEmail.
 *
 * @package App\Listeners\Incidents
 */
class SendIncidentAssignedEmail
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
        Mail::to($event->incident->user)->cc(Setting::get('incidents_manager_email'))->queue(new IncidentAssigned($event->incident));
    }
}
