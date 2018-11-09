<?php

namespace Tests\Feature\Tenants\Api;

use App\Mail\IncidentCreated;
use App\Mail\IncidentDeleted;
use App\Models\Incident;
use App\Models\User;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class IncidentsControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
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
    public function can_list_incidents()
    {
        $user = factory(User::class)->create([
            'name' => 'Carles Puigdemont'
        ]);
        $another_user = factory(User::class)->create([
            'name' => 'Carme Forcadell'
        ]);
        Incident::create([
            'subject' => 'No funciona pc2 Aula 36',
            'description' => 'bla bla bla'
        ])->assignUser($user);
        Incident::create([
            'subject' => 'No funciona pc1 Aula 20',
            'description' => 'ji ji ji'
        ])->assignUser($user);
        Incident::create([
            'subject' => 'No funciona projector sala Mestral',
            'description' => 'jorl jorl jorl'
        ])->assignUser($another_user);

        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $response =  $this->json('GET','/api/v1/incidents');
        $response->assertSuccessful();
        $incidents = json_decode($response->getContent());
        $this->assertCount(3,$incidents);

        $this->assertEquals($user->id,$incidents[0]->user_id);
        $this->assertEquals('Carles Puigdemont',$incidents[0]->user_name);
        $this->assertEquals('No funciona pc2 Aula 36',$incidents[0]->subject);
        $this->assertEquals('bla bla bla',$incidents[0]->description);
        $this->assertEquals($user->id,$incidents[1]->user_id);
        $this->assertEquals('Carles Puigdemont',$incidents[1]->user_name);
        $this->assertEquals('No funciona pc1 Aula 20',$incidents[1]->subject);
        $this->assertEquals('ji ji ji',$incidents[1]->description);
        $this->assertEquals($another_user->id,$incidents[2]->user_id);
        $this->assertEquals('Carme Forcadell',$incidents[2]->user_name);
        $this->assertEquals('No funciona projector sala Mestral',$incidents[2]->subject);
        $this->assertEquals('jorl jorl jorl',$incidents[2]->description);
    }

    /**
     * @test
     */
    public function regular_user_cannot_list_incidents()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response =  $this->json('GET','/api/v1/incidents');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function can_store_incidents()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        Mail::fake();
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');

        $response =  $this->json('POST','/api/v1/incidents',$incident = [
            'subject' => 'Ordinador Aula 36 no funciona',
            'description' => "El ordinador de l'aula 36 bla bla la"
        ]);
        $response->assertSuccessful();
        $createdIncident = json_decode($response->getContent());

        Mail::assertQueued(IncidentCreated::class, function ($mail) use ($createdIncident, $user) {
            return $mail->incident->id === $createdIncident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });

        $this->assertEquals($createdIncident->subject,'Ordinador Aula 36 no funciona');
        $this->assertEquals($createdIncident->description,"El ordinador de l'aula 36 bla bla la");
        $this->assertEquals($createdIncident->user_id,$user->id);
        $this->assertEquals($createdIncident->user_name,$user->name);
        $this->assertEquals($createdIncident->user_email,$user->email);

        $this->assertDatabaseHas('incidents',$incident);

        $user = $user->fresh();

        $this->assertCount(1,$user->incidents);
        $this->assertTrue(Incident::first()->is($user->incidents->first()));

    }

    /**
     * @test
     */
    public function can_store_incidents_validation()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $response =  $this->json('POST','/api/v1/incidents',[]);
        $response->assertStatus(422);

    }

    /**
     * @test
     */
    public function regular_user_cannot_store_incidences()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $response =  $this->json('POST','/api/v1/incidents', [
            'subject' => 'Ordinador Aula 36 no funciona',
            'description' => "El ordinador de l'aula 36 bla bla la"
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function can_show_incidence()
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
        $incidentUser = factory(User::class)->create([
            'name' => 'Carles Puigdemont',
            'email' => 'krls@republicatalana.cat'
        ]);
        $incident->assignUser($incidentUser);
        $response =  $this->json('GET','/api/v1/incidents/' . $incident->id);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($incidentUser->id,$result->user_id);
        $this->assertEquals('Carles Puigdemont',$result->user_name);
        $this->assertEquals('krls@republicatalana.cat',$result->user_email);
        $this->assertEquals('No funciona Aula 36 pc 1',$result->subject);
        $this->assertNull($result->closed_at);
        $this->assertNotNull($result->created_at);
        $this->assertNotNull($result->updated_at);
    }

    /**
     * @test
     */
    public function regular_user_cannot_show_incidence()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $response =  $this->json('GET','/api/v1/incidents/' . $incident->id);
        $response->assertStatus(403);
    }


    /**
     * @test
     */
    public function manager_can_delete_incidents()
    {
        $this->withoutExceptionHandling();
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

        Mail::fake();
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');

        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id);
        Mail::assertQueued(IncidentDeleted::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
        $response->assertSuccessful();
        $incident = $incident->fresh();
        $this->assertNull($incident);
    }

}
