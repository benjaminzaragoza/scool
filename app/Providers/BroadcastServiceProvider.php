<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // IMPORTANT! TENANT APP -> Broadcast routes registered on web.php inside tenant group -> Avoid errors with Auh user null because custom User model
//        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
