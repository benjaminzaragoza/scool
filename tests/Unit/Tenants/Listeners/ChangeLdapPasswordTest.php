<?php

namespace Tests\Unit\Tenants\Listeners;

use App\Events\Users\Password\UserPasswordChangedByManager;
use App\Listeners\Users\Password\ChangeLdapPassword;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery as m;

/**
 * Class ChangeLdapPassword.
 *
 * @package Tests\Unit
 */
class ChangeLdapPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate',[
            '--path' => 'database/migrations/tenant'
        ]);

        $this->app[Kernel::class]->setArtisan(null);
    }

    /** @test */
    public function change_ldap_password()
    {
        $listener = new ChangeLdapPassword();
        $user = factory(User::class)->create();
        $options= [];
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
    }

    /** @test */
    public function dont_change_ldap_password_if_not_required()
    {
        $listener = new ChangeLdapPassword();
        $user = factory(User::class)->create();
        $options= [];
        $ldapUser = m::mock('LdapUser');
        $ldapUser->shouldNotReceive('changePassword');
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
        $options= ['moodle' => true];
        $ldapUser->shouldNotReceive('changePassword');
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
        $options= ['ldap' => false];
        $ldapUser->shouldNotReceive('changePassword');
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
    }
}
