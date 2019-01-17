<?php

namespace Tests\Feature\Tenants\Web\Users;

use App\Models\User;
use App\Models\UserType;
use Config;
use Spatie\Permission\Models\Role;
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
    public function users_manager_can_see_roles_management()
    {
        $this->withoutExceptionHandling();
        $this->loginAsUsersManager('web');

        $response = $this->get('/users/roles');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.users.show');
        $response->assertViewHas('users');
        $response->assertViewHas('userTypes');
        $response->assertViewHas('roles');
    }

    /**
     * @test
     * @group users
     */
    public function super_admin_can_see_roles_management()
    {
        $this->loginAsSuperAdmin('web');
        $response = $this->get('/users/roles');
        $response->assertSuccessful();
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_see_roles_management()
    {
        $this->login('web');
        $response = $this->get('/users/roles');
        $response->assertStatus(403);
    }
}
