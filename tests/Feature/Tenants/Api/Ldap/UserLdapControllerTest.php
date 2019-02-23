<?php

namespace Tests\Feature\Tenants\Ldap;

use App\Events\Ldap\LdapUserAssociated;
use App\Events\Ldap\LdapUserUnAssociated;
use App\Models\LdapUser;
use App\Models\User;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserLdapControllerTest.
 *
 * @package Tests\Feature
 */
class UserLdapControllerTest extends BaseTenantTest
{
    use RefreshDatabase,CanLogin;

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
    public function can_associate_ldap_user_to_user()
    {
        $this->withoutExceptionHandling();
        $this->loginAsUsersManager('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);
        Event::fake();
        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/ldap', [
            'dn' => 'cn=Pepe,dc=iesebre,dc=com'
        ]);

        $response->assertSuccessful();
        Event::assertDispatched(LdapUserAssociated::class, function ($event) use ($user) {
            return $event->user->id === $user->id &&
                $event->ldapUser->dn === 'cn=Pepe,dc=iesebre,dc=com';
        });

        $user = $user->fresh();
        $this->assertEquals($user->id, $user->ldapUser->user_id);
        $this->assertEquals('cn=Pepe,dc=iesebre,dc=com', $user->ldapUser->dn);
    }

    /** @test */
    public function can_associate_ldap_user_to_user_validation()
    {
        $this->loginAsUsersManager('api');


        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);

        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/ldap');

        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_cannot_associate_ldap_user_to_user()
    {
        $this->login('api');
        $user = factory(User::class)->create();
        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/ldap');
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_associate_ldap_user_to_user()
    {
        $user = factory(User::class)->create();
        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/ldap');
        $response->assertStatus(401);
    }

    /** @test */
    public function can_unassociate_ldap_user_to_user()
    {
        $this->loginAsUsersManager('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);

        LdapUser::create([
            'user_id' => $user->id,
            'ldap_id' => 89,
            'ldap_username' => $user->email
        ]);

        $this->assertEquals(89,$user->ldapUser->ldap_id);
        Event::fake();
        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/ldap');

        $response->assertSuccessful();
        Event::assertDispatched(LdapUserUnAssociated::class, function ($e) use ($user) {
            return $e->user->id === $user->id &&
                intval($e->ldapUser) === 89;
        });
        $user = $user->fresh();
        $this->assertNull($user->ldapUser);
    }

    /** @test */
    public function regular_user_cannot_unassociate_ldap_user_to_user()
    {
        $this->login('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);

        LdapUser::create([
            'user_id' => $user->id,
            'ldap_id' => 89,
            'ldap_username' => $user->email
        ]);

        $this->assertEquals(89,$user->ldapUser->ldap_id);

        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/ldap');

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_unassociate_ldap_user_to_user()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);

        LdapUser::create([
            'user_id' => $user->id,
            'ldap_id' => 89,
            'ldap_username' => $user->email
        ]);

        $this->assertEquals(89,$user->ldapUser->ldap_id);

        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/ldap');

        $response->assertStatus(401);
    }
}
