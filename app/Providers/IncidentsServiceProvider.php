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

    protected function setIncidentsManagerEmail(Request $request)
    {
        if (env('APP_ENV') === 'testing') return;
//        $tenant = tenant_from_url();
//        if (! is_null($tenant)) {
//            if ($tenant = get_tenant($tenant)) {
//                $tenant->connect();
//                $tenant->configure();
//            }
//            if ($email = Setting::get('incidents_manager_email')) {
//                Config::set('incidents.manager_email', $email);
//            }
//        }
    }
}
