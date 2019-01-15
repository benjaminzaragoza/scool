<?php

namespace App\Providers;

use App\Models\Setting;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

/**
 * Class PositionsServiceProvider.
 *
 * @package App\Providers
 */
class PositionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $this->setPositionsManagerEmail($request);
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
    protected function setPositionsManagerEmail(Request $request)
    {
        $tenant = tenant_from_current_url();
        if (! is_null($tenant)) {
            $previousConnection = config('database.default');
            apply_tenant($tenant);
            if ($email = Setting::get('position_manager_email')) {
                Config::set('position.manager_email', $email);
            }
            restore_connect($previousConnection);
         }
    }
}
