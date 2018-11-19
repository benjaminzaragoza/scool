<?php

namespace Tests\Feature\Tenants;

use App\Events\Incidents\IncidentShowed;
use App\Models\Incident;
use App\Models\User;
use Config;
use Event;
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
        $this->actingAs($user);
        $response = $this->get('/incidents');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.incidents.index');
        $response->assertViewHas('incidents');
        $response->assertViewHas('incident_users');
        $response->assertViewHas('tags');
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

    /**
     * @test
     */
    public function can_show_incident()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');
        $incident= Incident::create([
            'subject' => "No funciona res a l'aula 45",
            'description' => 'Bla bla bla'
        ])->assignUser($otherUser);

        $response = $this->get('/incidents/' . $incident->id);
        Event::fake();
        $response->assertSuccessful();
        Event::assertDispatched(IncidentShowed::class,function ($event) use ($incident){
            return $event->incident->is($incident);
        });
        $response->assertViewIs('tenants.incidents.index');
        $response->assertViewHas('incidents');
        $response->assertViewHas('incident');
        $response->assertViewHas('incident_users');
        $response->assertViewHas('tags');
    }
}
