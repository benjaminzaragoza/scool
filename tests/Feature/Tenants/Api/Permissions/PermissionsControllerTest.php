<?php

namespace Tests\Feature\Tenants\Api\Permissions;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PermissionsControllerTest.
 *
 * @package Tests\Feature
 */
class PermissionsControllerTest extends BaseTenantTest
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
    public function superadmin_can_index_permissions()
    {
        sample_permissions();
        $this->loginAsSuperAdmin('api');

        $response = $this->json('GET','/api/v1/permissions');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3, $result);
        $this->assertEquals('Permission1', $result[0]->name);
        $this->assertEquals('web', $result[0]->guard_name);
        $this->assertEquals('permissions', $result[0]->api_uri);
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
    public function users_manager_can_index_permissions()
    {
        sample_permissions();
        $this->loginAsUsersManager('api');

        $response = $this->json('GET','/api/v1/permissions');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(4, $result);
        $this->assertEquals('Permission1', $result[0]->name);
        $this->assertEquals('web', $result[0]->guard_name);
        $this->assertEquals('permissions', $result[0]->api_uri);
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
    public function regular_user_cannot_see_permissions_management()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/permissions');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_see_permissions_management()
    {
        $response = $this->json('GET','/api/v1/permissions');
        $response->assertStatus(401);
    }
}
