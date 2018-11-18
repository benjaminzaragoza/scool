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
 * Class LogLoginUser.
 * @package App\Listeners\Authentication
 */
class LogLoginUser
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
        if (!Schema::hasTable('mytable')) return;

        Log::create([
            'text' => "L'usuari/a <strong>" . $event->user->name . '</strong> ha entrat al sistema',
            'time' => Carbon::now(),
            'action_type' => 'enter',
            'user_id' => $event->user->id,
            'module_type' => 'UsersManagment',
            'loggable_id' => $event->user->id,
            'loggable_type' => User::class,
            'persistedLoggable' => method_exists($event->user,'map') ? json_encode($event->user->map()) : $event->user->toJson(),
            'icon' => 'input',
            'color' => 'teal',
        ]);

    }
}
