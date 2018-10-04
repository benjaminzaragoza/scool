<?php

namespace Tests\Feature\Tenants;

use App\Models\Incident;
use App\Models\User;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class IncidentsControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class IncidentsControllerTest extends BaseTenantTest {

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
     */
    public function can_see_incidents()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');
        $response = $this->get('/incidents');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.incidents.index');
        $response->assertViewHas('incidents');
    }

    /**
     * @test
     */
    public function user_without_permissions_cannot_see_incidents()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/incidents');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function unlogged_user_cannot_see_incidents()
    {
        $response = $this->get('/incidents');
        // Redirected to login
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }


}