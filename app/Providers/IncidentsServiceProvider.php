<?php

namespace App\Providers;

use App\Models\Setting;
use Config;
use Illuminate\Http\Request;
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
    public function boot(Request $request)
    {
        $this->setIncidentsManagerEmail($request);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     *
     * @param Request $request
     */
    protected function setIncidentsManagerEmail(Request $request)
    {
        $tenant = tenant_from_current_url();
        if (! is_null($tenant)) {
            apply_tenant($tenant);
            if ($email = Setting::get('incidents_manager_email')) {
                Config::set('incidents.manager_email', $email);
            }
            main_connect();
         }
    }
}
