<?php

namespace App\Listeners\Positions;

use App\Mail\Positions\PositionAssigned;
use App\Models\Setting;
use Mail;

/**
 * Class SendPositionAssignedEmail.
 *
 * @package App\Listeners\Positions
 */
class SendPositionAssignedEmail
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
        Mail::to($event->position->user)->cc(Setting::get('positions_manager_email'))
            ->queue((new PositionAssigned($event->position))->onQueue(tenant_from_current_url()));
    }
}
