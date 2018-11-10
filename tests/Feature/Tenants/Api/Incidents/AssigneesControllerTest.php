<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Mail\Incidents\IncidentAssigned;
use App\Mail\Incidents\IncidentDesassigned;
use App\Models\Incident;
use App\Models\User;
use Config;
use Mail;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class AssigneesControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class AssigneesControllerTest extends BaseTenantTest{

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
    public function can_assignee_incident_to_user()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');
        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $assignee = factory(User::class)->create();
        $this->assertCount(0,$incident->assignees);
        Mail::fake();
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        $response = $this->json('POST','/api/v1/incidents/' . $incident->id . '/assignees/' . $assignee-> id);
        $response->assertSuccessful();
        Mail::assertQueued(IncidentAssigned::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
        $incident = $incident->fresh();
        $this->assertCount(1,$incident->assignees);
        $this->assertTrue($incident->assignees[0]->is($assignee));
    }

    /** @test */
    public function incidents_users_cannot_assignee_incident_to_user()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');
        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $assignee = factory(User::class)->create();

        $this->assertCount(0,$incident->assignees);
        $response = $this->json('POST','/api/v1/incidents/' . $incident->id . '/assignees/' . $assignee-> id);
        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_assignee_incident_to_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $assignee = factory(User::class)->create();

        $this->assertCount(0,$incident->assignees);
        $response = $this->json('POST','/api/v1/incidents/' . $incident->id . '/assignees/' . $assignee-> id);
        $response->assertStatus(403);
    }

    /** @test */
    public function can_desassignee_incident_to_user()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');
        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $assignee = factory(User::class)->create();
        $incident->addAssignee($assignee);
        $this->assertCount(1,$incident->assignees);
        Mail::fake();
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/assignees/' . $assignee-> id);
        $response->assertSuccessful();
        Mail::assertQueued(IncidentDesassigned::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
        $incident = $incident->fresh();
        $this->assertCount(0,$incident->assignees);
    }

    /** @test */
    public function incidents_users_cannot_desassignee_incident_to_user()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');
        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $assignee = factory(User::class)->create();
        $incident->addAssignee($assignee);
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/assignees/' . $assignee-> id);
        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_desassignee_incident_to_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $assignee = factory(User::class)->create();
        $incident->addAssignee($assignee);
        $this->assertCount(1,$incident->assignees);
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/assignees/' . $assignee-> id);
        $response->assertStatus(403);
    }
}
