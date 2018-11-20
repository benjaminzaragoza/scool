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
            'old_loggable' => null,
            'new_loggable' => json_encode($event->incident->map()),
            'icon' => 'add',
            'color' => 'success'
        ]);
    }

    /**
     * Closed.
     *
     * @param $event
     */
    public static function closed($event)
    {
        Log::create([
            'text' => 'Ha tancat la incidència ' . $event->incident->link(),
            'time' => $event->incident->updated_at,
            'action_type' => 'close',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'old_loggable' => json_encode($event->oldIncident->map()),
            'new_loggable' => json_encode($event->incident->map()),
            'new_value' => 'Tancada',
            'old_value' => 'Oberta',
            'icon' => 'lock',
            'color' => 'success'
        ]);
    }

    /**
     * Opened.
     *
     * @param $event
     */
    public static function opened($event)
    {
        Log::create([
            'text' => 'Ha reobert la incidència ' . $event->incident->link(),
            'time' => $event->incident->updated_at,
            'action_type' => 'open',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'old_loggable' => json_encode($event->oldIncident->map()),
            'new_loggable' => json_encode($event->incident->map()),
            'old_value' => 'Tancada',
            'new_value' => 'Oberta',
            'icon' => 'lock_open',
            'color' => 'purple'
        ]);
    }

    /**
     * Showed.
     *
     * @param $event
     */
    public static function showed($event)
    {
        Log::create([
            'text' => 'Ha visitat la incidència ' . $event->incident->link(),
            'time' => Carbon::now(),
            'action_type' => 'show',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'new_loggable' => json_encode($event->incident->map()),
            'old_loggable' => json_encode($event->incident->map()),
            'icon' => 'visibility',
            'color' => 'primary'
        ]);
    }

    /**
     * Deleted.
     *
     * @param $event
     */
    public static function deleted($event)
    {
        Log::create([
            'text' => 'Ha eliminat la incidència ' . $event->oldIncident->subject,
            'time' => Carbon::now(),
            'action_type' => 'delete',
            'module_type' => 'Incidents',
            'user_id' => $event->oldIncident->user->id,
            'loggable_id' => $event->oldIncident->id,
            'loggable_type' => Incident::class,
            'old_loggable' => json_encode($event->oldIncident->map()),
            'new_loggable' => null,
            'icon' => 'remove',
            'color' => 'error'
        ]);
    }

    /**
     * descriptionUpdated.
     *
     * @param $event
     */
    public static function descriptionUpdated($event)
    {
        Log::create([
            'text' => 'Ha modificat la descripció de la incidència ' . $event->incident->link(),
            'time' => Carbon::now(),
            'action_type' => 'delete',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'old_loggable' => json_encode($event->oldIncident->map()),
            'new_loggable' => json_encode($event->incident->map()),
            'old_value' => $event->oldIncident->description,
            'new_value' => $event->incident->description,
            'icon' => 'remove',
            'color' => 'error'
        ]);
    }

    /**
     * subjectUpdated.
     *
     * @param $event
     */
    public static function subjectUpdated($event)
    {
        Log::create([
            'text' => 'Ha modificat el títol de la incidència ' . $event->incident->link(),
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'old_loggable' => json_encode($event->oldIncident->map()),
            'new_loggable' => json_encode($event->incident->map()),
            'old_value' => $event->oldIncident->subject,
            'new_value' => $event->incident->subject,
            'icon' => 'edit',
            'color' => 'primary'
        ]);
    }

    /**
     * replyAdded.
     *
     * @param $event
     */
    public static function replyAdded($event)
    {
        Log::create([
            'text' => 'Ha afegit un comentari a la incidència ' . $event->incident->link(),
            'time' => Carbon::now(),
            'action_type' => 'comment',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'old_loggable' => json_encode($event->oldIncident->map()),
            'new_loggable' => json_encode($event->incident->map()),
            'old_value' => null,
            'new_value' => $event->reply->body,
            'icon' => 'comment',
            'color' => 'primary'
        ]);
    }

    /**
     * replyUpdated.
     *
     * @param $event
     */
    public static function replyUpdated($event)
    {
        Log::create([
            'text' => 'Ha actualitzat un comentari a la incidència ' . $event->incident->link(),
            'time' => Carbon::now(),
            'action_type' => 'comment',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'old_loggable' => json_encode($event->oldIncident->map()),
            'new_loggable' => json_encode($event->incident->map()),
            'old_value' => $event->oldReply->body,
            'new_value' => $event->reply->body,
            'icon' => 'comment',
            'color' => 'primary'
        ]);
    }

    /**
     * replyRemoved.
     *
     * @param $event
     */
    public static function replyRemoved($event)
    {
        Log::create([
            'text' => 'Ha esborrat un comentari a la incidència ' . $event->incident->link(),
            'time' => Carbon::now(),
            'action_type' => 'comment',
            'module_type' => 'Incidents',
            'user_id' => $event->incident->user->id,
            'loggable_id' => $event->incident->id,
            'loggable_type' => Incident::class,
            'old_loggable' => json_encode($event->oldIncident->map()),
            'new_loggable' => json_encode($event->incident->map()),
            'old_value' => $event->oldReply->body,
            'icon' => 'comment',
            'color' => 'primary'
        ]);
    }
}
