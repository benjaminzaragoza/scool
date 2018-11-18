<?php

namespace App\Listeners\Authentication;

use App\Models\Log;
use App\Models\User;
use Auth;
use Carbon\Carbon;
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
        if (!Schema::hasTable('logs')) return;
        Log::create([
            'text' => "Intent de login incorrecte amb l'usuari <strong>" . $event->credentials['email'] . "</strong>",
            'time' => Carbon::now(),
            'action_type' => 'error',
            'module_type' => 'UsersManagment',
            'icon' => 'error',
            'color' => 'error',
        ]);

    }
}
