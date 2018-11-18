<?php

namespace App\Listeners\Authentication;

use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Schema;

/**
 * Class LogTakeImpersonation.
 *
 * @package App\Listeners\Authentication
 */
class LogTakeImpersonation
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
            'text' => "L'usuari/a admin <strong>" . $event->impersonator->name . ' - ' . $event->impersonator->email . "</strong> s'esta fent passar per <strong>" . $event->impersonated->name . ' - ' . $event->impersonated->email . '</strong>',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'user_id' => $event->impersonator->id,
            'module_type' => 'UsersManagment',
            'loggable_id' => $event->impersonator->id,
            'loggable_type' => User::class,
            'persistedLoggable' => method_exists($event->impersonator,'map') ? json_encode($event->impersonator->map()) : $event->impersonator->toJson(),
            'icon' => 'edit',
            'color' => 'teal',
        ]);

    }
}
