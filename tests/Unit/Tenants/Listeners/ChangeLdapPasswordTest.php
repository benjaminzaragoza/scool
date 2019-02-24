<?php

namespace Tests\Unit\Tenants\Listeners;

use Adldap\Models\ModelNotFoundException;
use App\Events\Users\Password\UserPasswordChangedByManager;
use App\Listeners\Users\Password\ChangeLdapPassword;
use App\Models\LdapUser;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    /**
     * @test
     * @group ldap
     * @group slow
     */
    public function change_ldap_password()
    {
        // TODO -> CREATE LDAP USER TEST AND REMOVE
        $listener = new ChangeLdapPassword();
        $user = factory(User::class)->create();
        LdapUser::create([
            'user_id' => $user->id,
            'dn' => 'cn=Pepe Pardo,dc=iesebre,dc=com',
            'uid' => 'stur',
        ]);
        $user = $user->fresh();
        $options= ['ldap' => true];
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
    }


    /**
     * @test
     * @group ldap
     * @group slow
     */
    public function change_ldap_password_model_not_found_exception()
    {
        $listener = new ChangeLdapPassword();
        $user = factory(User::class)->create();
        $options= ['ldap' => true];
        try {
            $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
        } catch (ModelNotFoundException $e) {
            $this->assertInstanceOf(ModelNotFoundException::class,$e);
            return;
        }
        $this->fail('ModelNotFoundException not thrown');
    }

    /**
     * @test
     * @group ldap
     * @group slow
     */
    public function dont_change_ldap_password_if_not_required()
    {
        $listener = new ChangeLdapPassword();
        $user = factory(User::class)->create();
        $options= [];
        $ldapUser = m::mock('LdapUser');
        $ldapUser->shouldNotReceive('changePassword');
//        Cache::shouldReceive('get')
//            ->once()
//            ->with('key')
//            ->andReturn('value');
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
        $options= ['moodle' => true];
        $ldapUser->shouldNotReceive('changePassword');
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
        $options= ['ldap' => false];
        $ldapUser->shouldNotReceive('changePassword');
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
    }
}
