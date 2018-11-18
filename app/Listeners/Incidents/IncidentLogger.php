<?php

namespace App\Listeners\Incidents;

use App\Models\Incident;
use App\Models\Log;
use Carbon\Carbon;

/**
 * Class IncidentLogger.
 *
 * @package App\Listeners\Incidents
 */
class IncidentLogger
{
    /**
     * Incorrect attempt.
     *
     * @param $event
     */
    public static function stored($event)
    {
        Log::create([
            'text' => "Ha creat la incidència $event->incident->subject",
            'time' => $event->incident->created_at,
            'action_type' => 'store',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'persistedLoggable' => json_encode($event->incident->map()),
            'icon' => 'add',
            'color' => 'success'
        ]);
    }
}
