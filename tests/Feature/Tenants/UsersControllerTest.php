<?php

namespace Tests\Feature\Tenants;

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

    /** @test */
    public function user_manager_can_see_users()
    {
        $this->withoutExceptionHandling();
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $user2 = create(User::class);
        $user3 = create(User::class);

        $response = $this->json('GET','/api/v1/users');

        $response->assertSuccessful();
        $this->assertCount(3,json_decode($response->getContent()));

        $response->assertJsonStructure([[
            'id',
            'name',
            'email',
            'created_at',
            'updated_at',
            'formatted_created_at',
            'formatted_updated_at',
            'admin',
        ]]);

        foreach ( [$manager, $user2, $user3] as $user) {
            $response->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]);
        }
        $this->assertCount(3,json_decode($response->getContent()));
    }

    /** @test */
    public function user_manager_can_add_users()
    {
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('POST','/api/v1/users',[
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com'
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com',
            'id' => 2
        ]);
    }

    /** @test */
    public function user_manager_can_add_user_with_mobile()
    {
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('POST','/api/v1/users',[
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com',
            'mobile' => '679524789'
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com',
            'mobile' => '679524789',
            'id' => 2
        ]);
    }

    /** @test */
    public function user_manager_can_add_user_with_user_type_and_roles()
    {
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        Role::firstOrCreate(['name' => 'Role1','guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Role1','guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'Role2','guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Role2','guard_name' => 'api']);

        UserType::create(['name' => 'Alumne']);

        $response = $this->json('POST','/api/v1/users',[
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com',
            'type' => 'Alumne',
            'roles' => ['Role1','Role2']
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com',
            'roles' => [ 'Role1', 'Role2']
        ]);

    }

    /** @test */
    public function user_cannot_add_users()
    {
        $regularUser = create(User::class);
        $this->actingAs($regularUser,'api');
        Config::set('auth.providers.users.model', User::class);

        $response = $this->json('POST','/api/v1/users',[
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_manager_can_add_users_validate()
    {
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('POST','/api/v1/users');

        $response->assertStatus(422);

    }

    /** @test */
    public function user_with_role_manager_can_see_users_management()
    {
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

    /** @test */
    public function user_with_role_manager_can_delete_users()
    {
        $manager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $userToDelete = create(User::class);

        $response = $this->json('DELETE','/api/v1/users/' . $userToDelete->id);

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'name' => $userToDelete->name,
            'email' => $userToDelete->email,
            'id' => $userToDelete->id
        ]);
    }

    /** @test */
    public function user_cannot_delete_users()
    {
        $regularUser = create(User::class);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $this->actingAs($regularUser,'api');

        $userToDelete = create(User::class);

        $response = $this->json('DELETE','/api/v1/users/' . $userToDelete->id);

        $response->assertStatus(403);
    }


    /** @test */
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

    /** @test */
    public function user_without_role_manager_cannot_see_users_management()
    {
        $user = create(User::class);
        $this->actingAs($user);
        Config::set('auth.providers.users.model', User::class);

        $response = $this->get('/users');

        $response->assertStatus(403);
    }
}
