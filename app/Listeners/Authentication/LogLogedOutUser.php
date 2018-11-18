<?php

namespace App\Listeners\Authentication;

use App\Models\Log;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        Log::create([
            'text' => "L'usuari/a <strong>" . $event->user->name . '</strong> ha sortit del sistema',
            'time' => Carbon::now(),
            'action_type' => 'exit',
            'user_id' => $event->user->id,
            'module_type' => 'UsersManagment',
            'loggable_id' => $event->user->id,
            'loggable_type' => User::class,
            'persistedLoggable' => json_encode(Auth::user()->map()),
            'icon' => 'exit_to_app',
            'color' => 'purple',
        ]);

    }
}
