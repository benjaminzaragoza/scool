<?php

namespace Tests\Feature\Tenants\Web\Users;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UsersControllerTest.
 *
 * @package Tests\Feature
 */
class UsersControllerTest extends BaseTenantTest
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
    public function users_manager_can_see_users_management()
    {
        initialize_user_types();
        $user = $this->loginAsUsersManager('web');

        $response = $this->get('/users');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.users.show');
        $response->assertViewHas('users', function($returnedUsers) use ($user) {
            dd($returnedUsers[0]['roles']);
            return $returnedUsers[0]['id'] === $user->id;
        });
        $response->assertViewHas('userTypes',function($returnedUserTypes) {
            return $returnedUserTypes[0]['name'] === 'Professor/a';
        });
        $response->assertViewHas('roles',function($returnedRoles) {
            return $returnedRoles[0]->name === 'Teacher';
        });
    }

    /**
     * @test
     * @group users
     */
    public function super_admin_can_see_users_management()
    {
        $this->loginAsSuperAdmin('web');
        $response = $this->get('/users');
        $response->assertSuccessful();
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_see_users_management()
    {
        $this->login('web');
        $response = $this->get('/users');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_see_users_management()
    {
        $response = $this->get('/users');
        $response->assertRedirect('/login');
    }
}
