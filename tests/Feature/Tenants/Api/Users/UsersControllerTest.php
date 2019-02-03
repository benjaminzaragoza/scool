<?php

namespace Tests\Feature\Tenants\Api\Users;

use App\Models\User;
use App\Models\UserType;
use Config;
use Spatie\Permission\Models\Role;
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
     * @group users
     */
    public function user_manager_can_see_users()
    {
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

    /**
     * @test
     * @group users
     */
    public function incidents_manager_can_delete_multiple_users()
    {
        $this->loginAsUsersManager('api');

        $user1 = create(User::class);
        $user2 = create(User::class);
        $user3 = create(User::class);

        $this->assertCount(4,User::all());

        $response = $this->json('POST','/api/v1/users/multiple', [
            'users' => [ $user1->id, $user2->id, $user3->id ]
        ]);

        $response->assertSuccessful();
        $this->assertCount(1,User::all());
        $this->assertEquals(3,$response->getContent());

        $user1 = $user1->fresh();
        $user2 = $user2->fresh();
        $user3 = $user3->fresh();
        $this->assertNull($user1);
        $this->assertNull($user2);
        $this->assertNull($user3);
    }
    /**
     * @test
     * @group users
     */
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

    /**
     * @test
     * @group users
     */
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

    /**
     * @test
     * @group users
     */
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
        ]);
        $result = json_decode($response->getContent());
        $this->assertCount(2, $result->roles);
        $this->assertEquals('Role1', $result->roles[0]->name);
        $this->assertEquals('Role2', $result->roles[1]->name);
    }

    /**
     * @test
     * @group users
     */
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

    /**
     * @test
     * @group users
     */
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

    /**
     * @test
     * @group users
     */
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

    /**
     * @test
     * @group users
     */
    public function user_cannot_delete_users()
    {
        $regularUser = create(User::class);
        $this->actingAs($regularUser,'api');

        $userToDelete = create(User::class);

        $response = $this->json('DELETE','/api/v1/users/' . $userToDelete->id);

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_delete_users()
    {
        $userToDelete = create(User::class);
        $response = $this->json('DELETE','/api/v1/users/' . $userToDelete->id);
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group users
     */
    public function user_with_role_manager_can_delete_multiple_users()
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

    /**
     * @test
     * @group users
     */
    public function can_get_user_info()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->get('/api/v1/users/' . $user->id);
        $response->assertSuccessful();

        $user = json_decode($response->getContent());
        $this->assertEquals(2,$user->id);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('pepepardojeans@gmail.com',$user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertEquals('654789524',$user->mobile);
        $this->assertNull($user->last_login);
        $this->assertNull($user->last_login_ip);
        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
        $this->assertEmpty($user->roles);
        $this->assertNotNull($user->formatted_created_at);
        $this->assertNotNull($user->formatted_updated_at);
        $this->assertEquals(0,$user->admin);
        $this->assertEquals('Ay',$user->hashid);
    }

    /** @test
     *  @group users
     */
    public function regular_user_cannot_get_user_info()
    {
        $regularUser = $this->login('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->get('/api/v1/users/' . $user->id);
        $response->assertStatus(403);
    }

    /** @test
     *  @group users
     */
    public function regular_user_can_get_his_own_user_info()
    {
        $user = $this->login('api');
        $user->name = 'Pepe Pardo Jeans';
        $user->email = 'pepepardojeans@gmail.com';
        $user->mobile = '654789524';
        $user->save();

        $response = $this->get('/api/v1/users/' . $user->id);
        $response->assertSuccessful();

        $user = json_decode($response->getContent());
        $this->assertEquals(1,$user->id);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('pepepardojeans@gmail.com',$user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertEquals('654789524',$user->mobile);
        $this->assertNull($user->last_login);
        $this->assertNull($user->last_login_ip);
        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
        $this->assertEmpty($user->roles);
        $this->assertNotNull($user->formatted_created_at);
        $this->assertNotNull($user->formatted_updated_at);
        $this->assertEquals(0,$user->admin);
        $this->assertEquals('MX',$user->hashid);
    }
}
