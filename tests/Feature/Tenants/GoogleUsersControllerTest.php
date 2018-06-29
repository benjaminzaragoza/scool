<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleGroupsControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleGroupsControllerTest extends BaseTenantTest
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
     * @group slow
     */
    public function show_google_users()
    {
        $usersManager = create(User::class);
        $this->actingAs($usersManager);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->get('google_users');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.google_users.show');
        $response->assertViewHas('users', function($users) {
            return google_user_check($users[0]);
        });
    }

    /** @test */
    public function regular_user_cannot_show_google_users()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('google_users');

        $response->assertStatus(403);
    }

    /**
     * List users.
     *
     * @test
     * @group slow
     */
    public function list_users()
    {
//        config_google_api();
//
//        $usersManager = create(User::class);
//        $this->actingAs($usersManager,'api');
//        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
//        Config::set('auth.providers.users.model', User::class);
//        $usersManager->assignRole($role);
//        $response = $this->json('GET','/api/v1/gsuite/groups');
//        $response->assertSuccessful();
    }

    /**
     *
     * @test
     * @group slow
     */
    public function regular_user_cannot_list_users()
    {
//        config_google_api();
//
//        $user = create(User::class);
//        $this->actingAs($user,'api');
//        $response = $this->json('GET','/api/v1/gsuite/regular_user_cannot_list_users');
//        $response->assertStatus(403);
    }


}
