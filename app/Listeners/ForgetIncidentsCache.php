<?php

namespace App\Listeners;

use App\Models\Incident;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ForgetIncidentCache
 * @package App\Listeners
 */
class ForgetIncidentsCache
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
        Cache::forget(Incident::INCIDENTS_CACHE_KEY);
    }
}
