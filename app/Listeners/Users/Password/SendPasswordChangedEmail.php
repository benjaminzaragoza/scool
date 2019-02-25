<?php

namespace App\Listeners\Users\Password;

use App\Mail\Users\Password\PasswordChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

/**
 * Class SendPasswordChangedEmail
 * @package App\Listeners
 */
class SendPasswordChangedEmail implements ShouldQueue
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
        if ($this->isRequiredToSendEmail($event->options)) Mail::to($event->user)->queue(new PasswordChanged($event->user,$event->password));
    }

    /**
     * isRequiredToChangeMoodlePassword.
     *
     * @param $options
     * @return bool
     */
    private function isRequiredToSendEmail($options) {
        if (is_array($options)) if (array_key_exists('email',$options)) if ($options['email']) return true;
        return false;
    }
}
