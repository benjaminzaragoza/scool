<?php

namespace Tests\Feature\Tenants\Api\Roles;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class RolesControllerTest.
 *
 * @package Tests\Feature
 */
class RolesControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group users
     */
    public function superadmin_can_index_roles()
    {
        sample_roles();
        $this->loginAsSuperAdmin('api');

        $response = $this->json('GET','/api/v1/roles');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3, $result);
        $this->assertEquals('Rol1', $result[0]->name);
        $this->assertEquals('web', $result[0]->guard_name);
        $this->assertEquals('roles', $result[0]->api_uri);
        $this->assertNotNull($result[0]->created_at);
        $this->assertNotNull($result[0]->created_at_timestamp);
        $this->assertNotNull($result[0]->formatted_created_at);
        $this->assertNotNull($result[0]->formatted_created_at_diff);
        $this->assertNotNull($result[0]->updated_at);
        $this->assertNotNull($result[0]->updated_at_timestamp);
        $this->assertNotNull($result[0]->formatted_updated_at);
        $this->assertNotNull($result[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group users
     */
    public function users_manager_can_index_roles()
    {
        sample_roles();
        $this->loginAsUsersManager('api');

        $response = $this->json('GET','/api/v1/roles');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(4, $result);
        $this->assertEquals('Rol1', $result[0]->name);
        $this->assertEquals('web', $result[0]->guard_name);
        $this->assertEquals('roles', $result[0]->api_uri);
        $this->assertNotNull($result[0]->created_at);
        $this->assertNotNull($result[0]->created_at_timestamp);
        $this->assertNotNull($result[0]->formatted_created_at);
        $this->assertNotNull($result[0]->formatted_created_at_diff);
        $this->assertNotNull($result[0]->updated_at);
        $this->assertNotNull($result[0]->updated_at_timestamp);
        $this->assertNotNull($result[0]->formatted_updated_at);
        $this->assertNotNull($result[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_see_roles_management()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/roles');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_see_roles_management()
    {
        $response = $this->json('GET','/api/v1/roles');
        $response->assertStatus(401);
    }
}
