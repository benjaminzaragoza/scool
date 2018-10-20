<?php

namespace Tests\Feature\Tenants\Api;

use App\Models\Incident;
use App\Models\User;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class IncidentsDescriptionController.
 *
 * @package Tests\Feature\Tenants\Api
 */
class IncidentsDescriptionController extends BaseTenantTest {

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
    public function manager_can_update_incident_description()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);

        $this->actingAs($user,'api');

        $incident = Incident::create([
            'subject' => 'No funciona PC12 Aula 45',
            'description' => 'bla bla bla'
        ]);
        $response = $this->json('PUT','/api/v1/incidents/' . $incident->id . '/description',[
            'description' => 'JOR JOR JOR'
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($result->subject,$result->subject);
        $this->assertEquals($result->id,$incident->id);

        $incident = $incident->fresh();
        $this->assertEquals($incident->description,'JOR JOR JOR');
    }

    /**
     * @test
     */
    public function user_can_update_owned_incident_description()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);

        $this->actingAs($user,'api');

        $incident = Incident::create([
            'subject' => 'No funciona PC12 Aula 45',
            'description' => 'bla bla bla'
        ])->assignUser($user);

        $response = $this->json('PUT','/api/v1/incidents/' . $incident->id . '/description',[
            'description' => 'JOR JOR JOR'
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($result->subject,$result->subject);
        $this->assertEquals($result->id,$incident->id);

        $incident = $incident->fresh();
        $this->assertEquals($incident->description,'JOR JOR JOR');
    }

    /**
     * @test
     */
    public function user_cannot_update_not_owned_incident_description()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $otherUser = factory(User::class)->create([
            'name' => 'Carme Forcadell'
        ]);

        $this->actingAs($user,'api');

        $incident = Incident::create([
            'subject' => 'No funciona PC12 Aula 45',
            'description' => 'bla bla bla'
        ])->assignUser($otherUser);

        $response = $this->json('PUT','/api/v1/incidents/' . $incident->id . '/description',[
            'description' => 'JOR JOR JOR'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function manager_can_update_incident_description_validation()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);

        $this->actingAs($user,'api');

        $incident = Incident::create([
            'subject' => 'No funciona PC12 Aula 45',
            'description' => 'bla bla bla'
        ]);
        $response = $this->json('PUT','/api/v1/incidents/' . $incident->id . '/description',[]);
        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function regular_user_cannot_update_incident_description()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $this->actingAs($user,'api');

        $incident = Incident::create([
            'subject' => 'No funciona PC12 Aula 45',
            'description' => 'bla bla bla'
        ]);
        $response = $this->json('PUT','/api/v1/incidents/' . $incident->id . '/description',[]);
        $response->assertStatus(403);
    }
}
