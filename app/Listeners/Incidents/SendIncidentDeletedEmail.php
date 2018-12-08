<?php

namespace App\Listeners\Incidents;

use App\Mail\Incidents\IncidentDeleted;
use App\Models\Setting;
use Mail;

/**
 * Class SendIncidentDeletedEmail.
 *
 * @package App\Listeners\Incidents
 */
class SendIncidentDeletedEmail
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
        Mail::to($event->oldIncident->user)->cc(Setting::get('incidents_manager_email'))
            ->queue((new IncidentDeleted($event->oldIncident->only(
                ['id','user_id','subject','description','created_at','updated_at'])))->onQueue(tenant_from_current_url()));
    }
}
