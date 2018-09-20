<?php

namespace Tests\Feature\Tenants;


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
            'google_id' => '789466518897',
            'google_email' => 'pepepardo@iesebre.com'
        ]);

        $response->assertSuccessful();
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

}
