<?php

namespace App\Listeners;

use App\Mail\GoogleInvalidUserNotificationReceived;
use Mail;

/**
 * Class SendGoogleInvalidUserNotificationReceivedEmail.
 *
 * @package App\Listeners
 */
class SendGoogleInvalidUserNotificationReceivedEmail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if (config('scool.gsuite_notifications_send_email')) {
            Mail::to(config('scool.gsuite_notifications_email'))->queue(new GoogleInvalidUserNotificationReceived($event->request));
        }
    }
}
