<?php

namespace App\Providers;

use Laravel\Horizon\HorizonServiceProvider;
use Route;

/**
 * Class CustomHorizonServiceProvider.
 *
 * @package App\Providers
 */
class CustomHorizonServiceProvider extends HorizonServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Register the Horizon routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('horizon.uri', 'horizon'),
            'namespace' => 'Laravel\Horizon\Http\Controllers',
            'middleware' => config('horizon.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../../routes/horizon/web.php');
        });
    }
}
