<?php

namespace Tests\Feature\Tenants\Web\Users;

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
    public function users_manager_can_see_permissions_management()
    {
        $this->loginAsUsersManager('web');

        $response = $this->get('/users/permissions');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.users.permissions.index');
        $response->assertViewHas('permissions', function($permissions) {
            return count($permissions) === 10 &&
                $permissions[0]['id'] === 1 &&
                $permissions[0]['name'] === 'moodle.index' &&
                $permissions[0]['guard_name'] === 'web' &&
                $permissions[0]['api_uri'] === 'permissions';
        });
    }

    /**
     * @test
     * @group users
     */
    public function super_admin_can_see_permissions_management()
    {
        $this->loginAsSuperAdmin('web');
        $response = $this->get('/users/permissions');
        $response->assertSuccessful();
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_see_permissions_management()
    {
        $this->login('web');
        $response = $this->get('/users/permissions');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_see_permissions_management()
    {
        $response = $this->get('/users/permissions');
        $response->assertRedirect('/login');
    }
}
