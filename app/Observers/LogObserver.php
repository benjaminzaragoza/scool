<?php

namespace App\Observers;

use App;
use App\Events\LogCreated;
use App\Models\Log;
use Log as LaravelLog;

/**
 * Class LogObserver
 * @package App\Observers
 */
class LogObserver
{
    /**
     * Handle the log "created" event.
     *
     * @param  \App\Models\Log  $log
     * @return void
     */
    public function created(Log $log)
    {
        if (App::environment('testing')) return;
        event(new LogCreated($log));
    }
}
