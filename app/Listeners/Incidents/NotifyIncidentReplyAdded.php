<?php

namespace App\Listeners\Incidents;

use App\Notifications\Incidents\IncidentReplyAdded;

/**
 * Class NotifyIncidentReplyAdded.
 *
 * @package App\Listeners\Incidents
 */
class NotifyIncidentReplyAdded
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
        $event->incident->user->notify(new IncidentReplyAdded($event->incident));
    }
}
