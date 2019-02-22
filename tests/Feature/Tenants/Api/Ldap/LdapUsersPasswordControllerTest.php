<?php

namespace Tests\Feature\Tenants\Api\Ldap;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class LdapUsersPasswordControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class LdapUsersPasswordControllerTest extends BaseTenantTest {

    use RefreshDatabase, CanLogin;

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
    public function superadmin_can_change_ldap_user_password()
    {
        $this->loginAsSuperAdmin('api');
//        $ldapuser = create_sample_ldap_user();
        $ldapuser = (object) [
          'uid' => 'stur'
        ];
        $response =  $this->json('PUT','/api/v1/ldap/users/' . $ldapuser->uid . '/password', [
            'password' => 'topsecret'
        ]);
        $response->assertSuccessful();
//        TODO
//        ldap_user_remove($ldapuser->uid);
    }

    /**
     * @test
     * @group ldap
     * @group slow
     */
    public function users_manager_can_change_ldap_user_password()
    {
        $this->loginAsLdapManager('api');
        // TODO
//        $ldapuser = create_sample_ldap_user();
        $ldapuser = (object) [
            'uid' => 'stur'
        ];
        $response =  $this->json('PUT','/api/v1/ldap/users/' . $ldapuser->uid . '/password', [
            'password' => 'topsecret'
        ]);
        $response->assertSuccessful();
        // TODO
//        ldap_user_remove($ldapuser->uid);
    }

    /**
     * @test
     * @group ldap
     * @group slow
     */
    public function superadmin_can_change_ldap_user_password_validation()
    {
        $this->loginAsSuperAdmin('api');
        $response =  $this->json('PUT','/api/v1/ldap/users/stur/password', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group ldap
     * @group slow
     */
    public function regular_user_cannot_change_ldap_user_password_validation()
    {
        $this->login('api');
        $response =  $this->json('PUT','/api/v1/ldap/users/stur/password', []);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group ldap
     * @group slow
     */
    public function guest_user_cannot_change_ldap_user_password_validation()
    {
        $response =  $this->json('PUT','/api/v1/ldap/users/stur/password', []);
        $response->assertStatus(401);
    }

}
