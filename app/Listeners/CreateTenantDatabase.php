<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class CreateTenantDatabase.
 *
 * @package App\Listeners
 */
class CreateTenantDatabase
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        create_mysql_full_database(
            $event->tenant->database,
            $event->tenant->username ,
            $event->tenant->password,
            $event->tenant->hostname);
        create_admin_user_on_tenant($event->tenant->user, $event->tenant, $event->password);
    }
}
