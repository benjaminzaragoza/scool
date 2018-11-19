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
     * Stored.
     *
     * @param $event
     */
    public static function stored($event)
    {
        Log::create([
            'text' => 'Ha creat la incidència ' . $event->incident->link(),
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

    /**
     * Stored.
     *
     * @param $event
     */
    public static function closed($event)
    {
        Log::create([
            'text' => 'Ha tancat la incidència ' . $event->incident->link(),
            'time' => $event->incident->updated_at,
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'persistedLoggable' => json_encode($event->incident->map()),
            'icon' => 'lock',
            'color' => 'success'
        ]);
    }

    /**
     * Stored.
     *
     * @param $event
     */
    public static function opened($event)
    {
        Log::create([
            'text' => 'Ha reobert la incidència ' . $event->incident->link(),
            'time' => $event->incident->updated_at,
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'persistedLoggable' => json_encode($event->incident->map()),
            'icon' => 'lock_open',
            'color' => 'purple'
        ]);
    }
}
