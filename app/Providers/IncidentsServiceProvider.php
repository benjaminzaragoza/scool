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
        dump($tenant);
        if (! is_null($tenant)) {
            dump('prova2');
            if ($tenant = get_tenant($tenant)) {
                dd(1);
                $tenant->connect();
                $tenant->configure();
            }
            if ($email = Setting::get('incidents_manager_email')) {
                Config::set('incidents.manager_email', $email);
            }
        }
    }
}
