<?php

namespace App\Providers;

use App\Auth\ScoolUserProvider;
use App\Libraries\Sha1\HashManager;
use App\Models\User;
use Auth;
use Gate;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider.
 *
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Auth::provider('scool', function ($app, array $config) {
            return new ScoolUserProvider(app(Hasher::class), User::class);
        });

        initialize_gates();

    }
}
