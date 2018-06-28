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

    /** @test */
    public function show_google_groups()
    {
//        $this->withoutExceptionHandling();
        config_google_api_for_tests();

        $usersManager = create(User::class);
        $this->actingAs($usersManager);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->get('google_groups');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.google_groups.show');
        $response->assertViewHas('groups');
    }

    /** @test */
    public function regular_user_cannot_show_google_groups()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('google_groups');

        $response->assertStatus(403);
    }
}
