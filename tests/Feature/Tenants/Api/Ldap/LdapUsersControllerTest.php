<?php

namespace Tests\Feature\Tenants\Api\Ldap;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class LdapusersControllerTest.
 *
 * @package Tests\Feature
 */
class LdapusersControllerTest extends BaseTenantTest
{
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
     * @group slow
     * @group ldap
     */
    public function list_ldap_users()
    {
        $this->loginAsUsersManager('api');

        $response = $this->json('GET','/api/v1/ldap/users');

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertTrue(is_array($result));
        // TODO
//        $this->assertTrue(google_user_check($result[0]));
    }

    /**
     * @test
     * @group ldap
     */
    public function regular_user_cannot_list_ldap_users()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/ldap/users');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group slow
     * @group ldap
     */
    public function user_manager_can_see_ldap_users()
    {
        // TODO
        $this->markTestSkipped('TODO adldap2-laravel');
        $this->withoutExceptionHandling();
        $this->loginAsUsersManager('api');

        $response = $this->json('GET','/ldap_users');
        $response->assertSuccessful();

        // TODO
//        $user2 = create(User::class);
//        $user3 = create(User::class);
//
//        $response = $this->json('GET','/api/v1/users');
//
//        $response->assertSuccessful();
//        $this->assertCount(3,json_decode($response->getContent()));
//
//        $response->assertJsonStructure([[
//            'id',
//            'name',
//            'email',
//            'created_at',
//            'updated_at',
//            'formatted_created_at',
//            'formatted_updated_at',
//            'admin',
//        ]]);
//
//        foreach ( [$manager, $user2, $user3] as $user) {
//            $response->assertJsonFragment([
//                'id' => $user->id,
//                'name' => $user->name,
//                'email' => $user->email
//            ]);
//        }
//        $this->assertCount(3,json_decode($response->getContent()));
    }

    /**
     * @test
     * @group ldap
     */
    public function regular_user_cannot_see_ldap_users()
    {
        // TODO
        $this->markTestSkipped('TODO package Ldap adladap2');
        $this->withoutExceptionHandling();
        $this->login('api');


        $response = $this->json('GET','/ldap_users');

        $response->assertStatus(403);

    }

    /**
     * @test
     * @group slow
     * @group ldap
     */
    public function user_manager_can_create_ldap_users()
    {
        $this->withoutExceptionHandling();
        // TODO
        $this->markTestSkipped('TODO adldap2');
        $this->loginAsUsersManager('api');

        $response = $this->json('POST', '/api/v1/ldap/users', [

        ]);
        $response->assertSuccessful();
    }

    /**
     * @test
     * @group ldap
     */
    public function user_manager_can_create_ldap_users_validation()
    {
        // TODO
        $this->markTestSkipped('TODO package Ldap adladap2');
        $this->withoutExceptionHandling();
        $this->loginAsUsersManager('api');

        $response = $this->json('POST', '/api/v1/ldap/users', [

        ]);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group ldap
     */
    public function regular_user_cannot_create_ldap_users()
    {
        // TODO
        $this->markTestSkipped('TODO package Ldap adladap2');
        $this->login('api');

        $response = $this->json('POST', '/api/v1/ldap/users');
        $response->assertStatus(403);
    }


}
