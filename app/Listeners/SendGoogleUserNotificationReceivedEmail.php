<?php

namespace App\Listeners;

use App\Mail\GoogleUserNotificationReceived;
use Mail;

/**
 * Class SendGoogleUserNotificationsReceivedEmail.
 *
 * @package App\Listeners
 */
class SendGoogleUserNotificationReceivedEmail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to('stur@iesebre.com')->send(new GoogleUserNotificationReceived($event->request));
    }
}
