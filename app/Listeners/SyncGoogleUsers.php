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
        $googleHeaders = collect($event->request->headers)->filter(function ($header, $headerKey) {
            return starts_with($headerKey,'x-goog-resource-state');
        });
        $googleHeaders = $googleHeaders->map(function ($header) {
            return $header[0];
        });
        $type = '';
        if (array_key_exists('x-goog-resource-state',$googleHeaders->toArray())) {
            $type = $googleHeaders['x-goog-resource-state'];
        };
        if ($type != 'sync') SyncGoogleUsersJob::dispatch();
    }
}
