<?php

namespace App\Listeners\Incidents;

use App\Mail\Incidents\IncidentClosed;
use App\Mail\Incidents\IncidentCreated;
use App\Mail\Incidents\IncidentOpened;
use App\Models\Setting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
    }
}
