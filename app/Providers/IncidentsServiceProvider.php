<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

/**
 * Class IncidentsServiceProvider.
 *
 * @package App\Providers
 */
class IncidentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->setIncidentsManagerEmail();
    }

    protected function setIncidentsManagerEmail()
    {
        if ($email = Setting::get('incidents_manager_email')) {
            $this->app['config']['incidents']['incidents_manager_email'] = $email;
        }
    }
}
