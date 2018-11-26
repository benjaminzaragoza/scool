<?php

namespace App\Listeners\Authentication;

use App;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Schema;

/**
 * Class LogAttemptLoginUser.
 * @package App\Listeners\Authentication
 */
class LogAttemptLoginUser
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
        if (App::environment('testing')) return;
        if (!Schema::hasTable('logs')) return;
        AuthenticationLogger::incorrectAttempt($event);
    }
}
