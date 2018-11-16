<?php

namespace App\Observers;

use App;
use App\Models\Incident;
use App\Models\Log;
use Auth;

/**
 * Class IncidentsObserver
 * @package App\Observers
 */
class IncidentsObserver
{
    /**
     * Handle the incident "created" event.
     *
     * @param Incident $incident
     * @return void
     */
    public function created(Incident $incident)
    {
        if (App::environment('testing')) return;
        Log::create([
            'text' => "Ha creat la incidència $incident->subject",
            'time' => $incident->created_at,
            'action_type' => 'store',
            'module_type' => 'Incidents',
            'user_id' => optional(Auth::user())->id,
            'loggable_id' => $incident->id,
            'loggable_type' => Incident::class,
            'persistedLoggable' => $incident->toJson(),
            'icon' => 'add',
            'color' => 'success'
        ]);
    }

    /**
     * Handle the incident "updated" event.
     *
     * @param  \App\Incident  $incident
     * @return void
     */
    public function updated(Incident $incident)
    {
        //
    }

    /**
     * Handle the incident "deleted" event.
     *
     * @param  \App\Incident  $incident
     * @return void
     */
    public function deleted(Incident $incident)
    {
        //
    }

    /**
     * Handle the incident "restored" event.
     *
     * @param  \App\Incident  $incident
     * @return void
     */
    public function restored(Incident $incident)
    {
        //
    }

    /**
     * Handle the incident "force deleted" event.
     *
     * @param  \App\Incident  $incident
     * @return void
     */
    public function forceDeleted(Incident $incident)
    {
        //
    }
}
