<?php

namespace App\Listeners;

use App\Jobs\SyncGoogleUsers as SyncGoogleUsersJob;

/**
 * Class SyncGoogleUsers.
 *
 * @package App\Listeners
 */
class SyncGoogleUsers
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $type = $event->request->header('x-goog-resource-state',null);
        if ($type != 'sync' && $type != null) SyncGoogleUsersJob::dispatch();
    }
}
