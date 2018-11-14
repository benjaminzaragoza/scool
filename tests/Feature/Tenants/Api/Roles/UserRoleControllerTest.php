<?php

namespace Tests\Feature;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserRoleControllerTest.
 *
 * @package Tests\Feature
 */
class UserRoleControllerTest extends BaseTenantTest
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

    /** @test */
    public function superadmin_can_add_role_to_user()
    {
        $manager = factory(User::class)->create();
        $manager->admin = true;
        $manager->save();

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'ProvaRol']);
        $this->assertFalse($user->hasRole($roleToAdd));

        $this->actingAs($manager,'api');

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/' . $roleToAdd->id);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertTrue($user->hasRole($roleToAdd));
    }

    /** @test */
    public function roles_manager_can_add_role_to_user()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'RolesManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'ProvaRol', 'guard_name' => 'web']);
        $this->assertFalse($user->hasRole($roleToAdd));

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/' . $roleToAdd->id);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertTrue($user->hasRole($roleToAdd));
    }

    /** @test */
    public function incidents_manager_can_add_role_incidents_to_users()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'Incidents', 'guard_name' => 'web']);
        $this->assertFalse($user->hasRole($roleToAdd));

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/' . $roleToAdd->id);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertTrue($user->hasRole($roleToAdd));
    }

    /** @test */
    public function incidents_manager_cannot_add_other_roles_to_users()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'RolProva']);

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/' . $roleToAdd->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_add_other_roles_to_users()
    {
        $notalloweduser = factory(User::class)->create();
        $this->actingAs($notalloweduser,'api');

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'RolProva']);

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/' . $roleToAdd->id);
        $response->assertStatus(403);
    }
}
