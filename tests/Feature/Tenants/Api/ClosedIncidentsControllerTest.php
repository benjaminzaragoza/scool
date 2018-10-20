<?php

namespace Tests\Feature\Tenants\Api;

use App\Models\Incident;
use App\Models\User;
use Config;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class ClosedIncidentsControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class ClosedIncidentsControllerTest extends BaseTenantTest{

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
    public function user_can_close_owned_incidents()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $this->actingAs($user,'api');

        $incident= Incident::create([
            'subject' => "No funciona res a l'aula 45",
            'description' => 'Bla bla bla'
        ])->assignUser($user);

        $response = $this->json('POST','/api/v1/closed_incidents/' . $incident->id);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($incident->id, $result->id);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals("No funciona res a l'aula 45", $result->subject);
        $this->assertEquals('Bla bla bla', $result->description);
        $incident = $incident->fresh();
        $this->assertNotNull($incident->closed_at);
    }

    /**
     * @test
     */
    public function user_cannot_close_not_owned_incidents()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $otherUser = factory(User::class)->create([
            'name' => 'Carme Forcadell'
        ]);
        $this->actingAs($user,'api');

        $incident= Incident::create([
            'subject' => "No funciona res a l'aula 45",
            'description' => 'Bla bla bla'
        ])->assignUser($otherUser);

        $response = $this->json('POST','/api/v1/closed_incidents/' . $incident->id);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function incidents_manager_can_close_incidents()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $otherUser = factory(User::class)->create([
            'name' => 'Carme Forcadell'
        ]);
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);

        $this->actingAs($user,'api');

        $incident= Incident::create([
            'subject' => "No funciona res a l'aula 45",
            'description' => 'Bla bla bla'
        ])->assignUser($otherUser);

        $response = $this->json('POST','/api/v1/closed_incidents/' . $incident->id);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($incident->id, $result->id);
        $this->assertEquals($otherUser->id, $result->user_id);
        $this->assertEquals("No funciona res a l'aula 45", $result->subject);
        $this->assertEquals('Bla bla bla', $result->description);
        $incident = $incident->fresh();
        $this->assertNotNull($incident->closed_at);
    }

    /**
     * @test
     */
    public function cannot_close_an_unexisting_incident()
    {
        $response = $this->json('POST','/api/v1/closed_incident/1');
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function user_can_open_owned_incidents()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $this->actingAs($user,'api');

        $incident= Incident::create([
            'subject' => "No funciona res a l'aula 45",
            'description' => 'Bla bla bla'
        ])->assignUser($user);

        $response = $this->json('DELETE','/api/v1/closed_incidents/' . $incident->id);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($incident->id, $result->id);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals("No funciona res a l'aula 45", $result->subject);
        $this->assertEquals('Bla bla bla', $result->description);
        $incident = $incident->fresh();
        $this->assertNull($incident->closed_at);
    }

    /**
     * @test
     */
    public function user_cannot_open_not_owned_incidents()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $otherUser = factory(User::class)->create([
            'name' => 'Carme Forcadell'
        ]);
        $this->actingAs($user,'api');

        $incident= Incident::create([
            'subject' => "No funciona res a l'aula 45",
            'description' => 'Bla bla bla'
        ])->assignUser($otherUser);

        $response = $this->json('DELETE','/api/v1/closed_incidents/' . $incident->id);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function incidents_manager_can_open_incidents()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $otherUser = factory(User::class)->create([
            'name' => 'Carme Forcadell'
        ]);
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);

        $this->actingAs($user,'api');

        $incident= Incident::create([
            'subject' => "No funciona res a l'aula 45",
            'description' => 'Bla bla bla'
        ])->assignUser($otherUser);

        $response = $this->json('DELETE','/api/v1/closed_incidents/' . $incident->id);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($incident->id, $result->id);
        $this->assertEquals($otherUser->id, $result->user_id);
        $this->assertEquals("No funciona res a l'aula 45", $result->subject);
        $this->assertEquals('Bla bla bla', $result->description);
        $incident = $incident->fresh();
        $this->assertNull($incident->closed_at);
    }

    /**
     * @test
     */
    public function cannot_open_an_unexisting_incident()
    {
        $response = $this->json('DELETE','/api/v1/closed_incident/1');
        $response->assertStatus(404);
    }
}
