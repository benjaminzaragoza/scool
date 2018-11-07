<?php

namespace Tests\Feature\Tenants\Api;

use App\Models\Setting;
use App\Models\User;
use Config;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class SettingsControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class SettingsControllerTest extends BaseTenantTest{

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
    public function superadmin_can_update_a_setting_value()
    {
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');

        $this->assertEquals(Setting::get('incidents_manager_email'),'incidencies@iesebre.com');
        $this->assertEquals(Setting::getRole('incidents_manager_email'),'IncidentsManager');

        $user = factory(User::class)->create();
        $user->admin = true;
        $user->save();
        $this->actingAs($user,'api');

        $response = $this->json('PUT','/api/v1/settings/incidents_manager_email',[
            'value' => 'maninfo@iesebre.com'
        ]);
        $response->assertSuccessful();
        $this->assertEquals(Setting::get('incidents_manager_email'),'maninfo@iesebre.com');
        $this->assertEquals(Setting::getRole('incidents_manager_email'),'IncidentsManager');
    }

    /**
     * @test
     */
    public function user_with_role_can_update_a_setting_value()
    {
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');

        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);

        $this->actingAs($user,'api');

        $response = $this->json('PUT','/api/v1/settings/incidents_manager_email',[
            'value' => 'maninfo@iesebre.com'
        ]);
        $response->assertSuccessful();
        $this->assertEquals(Setting::get('incidents_manager_email'),'maninfo@iesebre.com');
        $this->assertEquals(Setting::getRole('incidents_manager_email'),'IncidentsManager');
    }

    /**
     * @test
     */
    public function regular_user_cannot_update_a_setting_value()
    {
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');

        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('PUT','/api/v1/settings/incidents_manager_email',[
            'value' => 'maninfo@iesebre.com'
        ]);
        $response->assertStatus(403);
    }
}
