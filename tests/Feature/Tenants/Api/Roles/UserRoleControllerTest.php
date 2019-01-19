<?php

namespace Tests\Feature\Tenant\Api\Roles;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserRoleControllerTest.
 *
 * @package Tests\Feature
 */
class UserRoleControllerTest extends BaseTenantTest
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
    public function superadmin_can_add_roles_to_user()
    {
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin('api');

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'ProvaRol','guard_name' => 'web']);
        $this->assertFalse($user->hasRole($roleToAdd));

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/multiple', [
            'roles' => ['ProvaRol']
        ]);

        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertTrue($user->hasRole($roleToAdd));
    }

    /** @test */
    public function superadmin_can_add_roles_to_user_validation()
    {
        $this->loginAsSuperAdmin('api');

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'ProvaRol']);
        $this->assertFalse($user->hasRole($roleToAdd));

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/multiple');

        $response->assertStatus(422);
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
    public function superadmin_can_add_role_to_user_usign_rolename()
    {
        $manager = factory(User::class)->create();
        $manager->admin = true;
        $manager->save();

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'ProvaRol']);
        $this->assertFalse($user->hasRole($roleToAdd));

        $this->actingAs($manager,'api');

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/' . $roleToAdd->name);
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
    public function incidents_manager_can_add_role_incidents_manager_to_users()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $roleToAdd = Role::firstOrCreate(['name' => 'IncidentsManager', 'guard_name' => 'web']);
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





















    /** @test */
    public function superadmin_can_remove_role_to_user()
    {
        $manager = factory(User::class)->create();
        $manager->admin = true;
        $manager->save();

        $user = factory(User::class)->create();
        $roleToRemove = Role::firstOrCreate(['name' => 'ProvaRol']);
        $user->assignRole($roleToRemove);
        $this->assertTrue($user->hasRole($roleToRemove));

        $this->actingAs($manager,'api');

        $response = $this->json('DELETE','/api/v1/user/' . $user->id . '/role/' . $roleToRemove->id);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertFalse($user->hasRole($roleToRemove));
    }

    /** @test */
    public function superadmin_can_remove_role_to_user_usign_rolename()
    {
        $manager = factory(User::class)->create();
        $manager->admin = true;
        $manager->save();

        $user = factory(User::class)->create();
        $roleToRemove = Role::firstOrCreate(['name' => 'ProvaRol']);
        $user->assignRole($roleToRemove);
        $this->assertTrue($user->hasRole($roleToRemove));

        $this->actingAs($manager,'api');

        $response = $this->json('DELETE','/api/v1/user/' . $user->id . '/role/' . $roleToRemove->name);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertFalse($user->hasRole($roleToRemove));
    }

    /** @test */
    public function roles_manager_can_remove_role_to_user()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'RolesManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $roleToRemove = Role::firstOrCreate(['name' => 'ProvaRol', 'guard_name' => 'web']);
        $user->assignRole($roleToRemove);
        $this->assertTrue($user->hasRole($roleToRemove));

        $response = $this->json('DELETE','/api/v1/user/' . $user->id . '/role/' . $roleToRemove->id);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertFalse($user->hasRole($roleToRemove));
    }

    /** @test */
    public function incidents_manager_can_remove_role_incidents_to_users()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $roleToRemove = Role::firstOrCreate(['name' => 'Incidents', 'guard_name' => 'web']);
        $user->assignRole($roleToRemove);
        $this->assertTrue($user->hasRole($roleToRemove));

        $response = $this->json('DELETE','/api/v1/user/' . $user->id . '/role/' . $roleToRemove->id);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertFalse($user->hasRole($roleToRemove));
    }

    /** @test */
    public function incidents_manager_cannot_remove_other_roles_to_users()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $roleToRemove = Role::firstOrCreate(['name' => 'RolProva']);

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/' . $roleToRemove->id);
        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_remove_other_roles_to_users()
    {
        $notalloweduser = factory(User::class)->create();
        $this->actingAs($notalloweduser,'api');

        $user = factory(User::class)->create();
        $roleToRemove = Role::firstOrCreate(['name' => 'RolProva']);

        $response = $this->json('POST','/api/v1/user/' . $user->id . '/role/' . $roleToRemove->id);
        $response->assertStatus(403);
    }
}
