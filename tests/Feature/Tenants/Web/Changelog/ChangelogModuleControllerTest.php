<?php

namespace Tests\Feature\Web\Api;

use App\Models\Module;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;

/**
 * Class ChangelogModuleControllerTest.
 *
 * @package Tests\Feature
 */
class ChangelogModuleControllerTest extends BaseTenantTest
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
    public function show_changelog_for_an_specific_module()
    {
        $logs = sample_logs();

        Module::firstOrCreate([
            'name' => 'incidents',
        ]);
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user);
        $response = $this->get('/changelog/module/incidents');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.changelog.modules.index');
        $response->assertViewHas('logs', function ($returnedLogs) use ($logs) {
            return
                $returnedLogs[0]['user_name'] === $logs[0]['user']->name &&
                $returnedLogs[0]['color'] === 'teal' &&
                $returnedLogs[0]['action_type'] === 'update' &&
                $returnedLogs[0]['text'] === "Ha creat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[0]['icon'] === 'home' &&
                $returnedLogs[1]['text'] === "Ha modificat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[1]['action_type'] === 'update' &&
                $returnedLogs[2]['text'] === "Ha modificat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[2]['action_type'] === 'update' &&
                count($returnedLogs) === 3;
        });
        $response->assertViewHas('users');
        $response->assertViewHas('module', function ($module) {
            return $module->name === 'incidents';
        });
    }

    /** @test */
    public function cannot_show_changelog_for_an_unexisting_module()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/changelog/module/nonexistingmodule');
        $response->assertStatus(404);
    }

    /** @test */
    public function guest_cannot_show_changelog()
    {
        Module::firstOrCreate([
            'name' => 'incidents',
        ]);
        $response = $this->get('/changelog/module/incidents');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function regular_user_cannot_show_changelog()
    {
        Module::firstOrCreate([
            'name' => 'incidents',
        ]);
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/changelog/module/incidents');
        $response->assertStatus(403);
    }
}
