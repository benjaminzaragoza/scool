<?php

namespace App\Listeners\Users\Password;

use Illuminate\Contracts\Queue\ShouldQueue;

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

    }
}
