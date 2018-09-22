<?php

namespace Tests\Feature\Tenants;


use App\Models\GoogleUser;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;

/**
 * Class UserGsuiteControllerTest.
 *
 * @package Tests\Feature
 */
class UserGsuiteControllerTest extends BaseTenantTest
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
    public function can_associate_gsuite_user_to_user()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager, 'api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/gsuite', [
            'google_id' => '104838924762351808886',
            'google_email' => 'pepepardo@iesebre.com'
        ]);

        $response->assertSuccessful();

        $user = $user->fresh();

        $this->assertEquals($user->id, $user->googleUser->user_id);
        $this->assertEquals('104838924762351808886', $user->googleUser->google_id);
        $this->assertEquals('pepepardo@iesebre.com', $user->googleUser->google_email);

    }

    /** @test */
    public function can_associate_gsuite_user_to_user_and_test_googleuser()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager, 'api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/gsuite', [
            'google_id' => '104838924762351808886',
            'google_email' => 'pepepardo@iesebre.com'
        ]);

        $response->assertSuccessful();

        $user = $user->fresh();

        $this->assertEquals($user->id, $user->googleUser->user_id);
        $this->assertEquals('104838924762351808886', $user->googleUser->google_id);
        $this->assertEquals('pepepardo@iesebre.com', $user->googleUser->google_email);

        // TODO -> Asser google user is modified (employeeId = user->id i personalEmail)
        $this->assertTrue(false);
    }

    /** @test */
    public function can_associate_gsuite_user_to_user_validation()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager, 'api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/gsuite');

        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_cannot_associate_gsuite_user_to_user()
    {
        $regularUser = factory(User::class)->create();
        $this->actingAs($regularUser, 'api');

        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/gsuite');

        $response->assertStatus(403);
    }

    /** @test */
    public function can_unassociate_gsuite_user_to_user()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager, 'api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        GoogleUser::create([
            'user_id' => $user->id,
            'google_id' => 7896454538713789,
            'google_email' => 'pepepardojeans@iesebre.com',
        ]);

        $this->assertEquals(7896454538713789,$user->googleUser->google_id);
        $this->assertEquals('pepepardojeans@iesebre.com',$user->googleUser->google_email);

        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/gsuite');

        $response->assertSuccessful();

        $user = $user->fresh();
        $this->assertNull($user->googleUser);
    }

    /** @test */
    public function regular_user_cannot_unassociate_gsuite_user_to_user()
    {
        $regularUser = factory(User::class)->create();
        $this->actingAs($regularUser, 'api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/gsuite');

        $response->assertStatus(403);
    }
}
