<?php

namespace Tests\Feature\Web\Changelog;

use App\Models\Incident;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;

/**
 * Class ChangelogLoggableControllerTest.
 *
 * @package Tests\Feature
 */
class ChangelogLoggableControllerTest extends BaseTenantTest
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
    public function show_changelog_for_an_specific_owned_loggable()
    {
        $logs = sample_logs();

        $user = User::findOrFail(1);
        $this->actingAs($user);
        $incident = Incident::first();
        $incident->assignUser($user);
        $response = $this->get('/changelog/loggable/incidents/' . $incident->id);
        $response->assertSuccessful();

        $response->assertViewIs('tenants.changelog.loggable.index');
        $response->assertViewHas('logs', function ($returnedLogs) use ($logs) {
            return
                $returnedLogs[0]['user_name'] === $logs[0]['user']->name &&
                $returnedLogs[0]['color'] === 'teal' &&
                $returnedLogs[0]['action_type'] === 'update' &&
                $returnedLogs[0]['text'] === "Ha creat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[0]['icon'] === 'home' &&
                count($returnedLogs) === 3;
        });
        $response->assertViewHas('users');
        $response->assertViewHas('loggable', function ($returnedLoggable) use ($incident) {
            return $returnedLoggable->is($incident);
        });
    }

    /** @test */
    public function manager_can_show_changelog_for_an_specific_loggable()
    {
        $logs = sample_logs();

        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user);
        $incident = Incident::first();
        $incident->assignUser(User::first());
        $response = $this->get('/changelog/loggable/incidents/' . $incident->id);
        $response->assertSuccessful();

        $response->assertViewIs('tenants.changelog.loggable.index');
        $response->assertViewHas('logs', function ($returnedLogs) use ($logs) {
            return
                $returnedLogs[0]['user_name'] === $logs[0]['user']->name &&
                $returnedLogs[0]['color'] === 'teal' &&
                $returnedLogs[0]['action_type'] === 'update' &&
                $returnedLogs[0]['text'] === "Ha creat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[0]['icon'] === 'home' &&
                count($returnedLogs) === 3;
        });
        $response->assertViewHas('users');
        $response->assertViewHas('loggable', function ($returnedLoggable) use ($incident) {
            return $returnedLoggable->is($incident);
        });
    }

    /** @test */
    public function incident_users_can_show_changelog_for_an_specific_loggable()
    {
        $logs = sample_logs();

        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user);
        $incident = Incident::first();
        $incident->assignUser($user);
        $response = $this->get('/changelog/loggable/incidents/' . $incident->id);
        $response->assertSuccessful();

        $response->assertViewIs('tenants.changelog.loggable.index');
        $response->assertViewHas('logs', function ($returnedLogs) use ($logs) {
            return
                $returnedLogs[0]['user_name'] === $logs[0]['user']->name &&
                $returnedLogs[0]['color'] === 'teal' &&
                $returnedLogs[0]['action_type'] === 'update' &&
                $returnedLogs[0]['text'] === "Ha creat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[0]['icon'] === 'home' &&
                count($returnedLogs) === 3;
        });
        $response->assertViewHas('users');
        $response->assertViewHas('loggable', function ($returnedLoggable) use ($incident) {
            return $returnedLoggable->is($incident);
        });
    }

    /** @test */
    public function guest_cannot_changelog()
    {
        $incident = Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla'
        ]);
        $response = $this->get('/changelog/loggable/incidents/' . $incident->id);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function cannot_changelog_for_an_unexisting_loggable()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/changelog/loggable/incidents/1');
        $response->assertStatus(404);
    }

    /** @test */
    public function regular_user_cannot_see_changelog_for_an_specific_loggable()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $incident = Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla'
        ]);
        $response = $this->get('/changelog/loggable/incidents/' . $incident->id);
        $response->assertStatus(403);
    }
}
