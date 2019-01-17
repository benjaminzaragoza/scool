<?php

namespace Tests\Feature\Tenants\Web\Users;

use App\Models\User;
use App\Models\UserType;
use Config;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class UsersControllerTest.
 *
 * @package Tests\Feature
 */
class UsersControllerTest extends BaseTenantTest
{
    use RefreshDatabase;

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
    public function user_with_role_manager_can_see_users_management()
    {
        $this->withoutExceptionHandling();
        $user = create(User::class);
        $this->actingAs($user);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);

        $response = $this->get('/users');

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
    public function super_admin_can_see_users_management()
    {
        $user = create(User::class);
        $user->admin = true;
        $user->save();
        $this->actingAs($user);

        Config::set('auth.providers.users.model', User::class);

        $response = $this->get('/users');

        $response->assertSuccessful();
    }

    /**
     * @test
     * @group users
     */
    public function user_without_role_manager_cannot_see_users_management()
    {
        $user = create(User::class);
        $this->actingAs($user);
        Config::set('auth.providers.users.model', User::class);

        $response = $this->get('/users');

        $response->assertStatus(403);
    }
}
