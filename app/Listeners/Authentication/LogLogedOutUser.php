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
 * Class LogLoggedOutUser.
 * @package App\Listeners\Authentication
 */
class LogLogedOutUser
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
            'text' => "L'usuari/a <strong>" . $event->user->name . '</strong> ha sortit del sistema',
            'time' => Carbon::now(),
            'action_type' => 'exit',
            'user_id' => $event->user->id,
            'module_type' => 'UsersManagment',
            'loggable_id' => $event->user->id,
            'loggable_type' => User::class,
            'persistedLoggable' => method_exists($event->user,'map') ? json_encode($event->user->map()) : $event->user->toJson(),
            'icon' => 'exit_to_app',
            'color' => 'purple',
        ]);

    }
}
