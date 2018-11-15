<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Mail\Incidents\IncidentCreated;
use App\Mail\Incidents\IncidentDeleted;
use App\Models\Incident;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class ChangelogControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class ChangelogControllerTest extends BaseTenantTest {

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
    public function can_list_logs()
    {
        $user = factory(User::class)->create();
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $this->actingAs($user,'api');
        $log1 = Log::create([
            'text' => 'Ha creat la incidència TODO_LINK_INCIDENCIA',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => $user1->id,
            'icon' => 'home',
            'color' => 'teal'
        ]);
        $log2 = Log::create([
            'text' => 'Ha modificat la incidència TODO_LINK_INCIDENCIA',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => $user2->id,
            'icon' => 'home',
            'color' => 'teal'
        ]);
        $log3 = Log::create([
            'text' => 'Ha modificat la incidència TODO_LINK_INCIDENCIA',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => $user2->id,
            'icon' => 'home',
            'color' => 'teal'
        ]);
        $logs = [$log1,$log2,$log3];
        $response =  $this->json('GET','/api/v1/changelog');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3,$result);

        $this->assertEquals($logs[0]->id,$result[0]->id);
        $this->assertEquals($logs[0]->text,$result[0]->text);
        $this->assertNotNull($result[0]->time);
        $this->assertNotNull($result[0]->human_time);
        $this->assertNotNull($result[0]->timestamp);
        $this->assertEquals($logs[0]->action_type, $result[0]->action_type);
        $this->assertEquals($logs[0]->module, $result[0]->module);
        $this->assertEquals($logs[0]->user_id, $result[0]->user_id);
        $this->assertEquals($logs[0]->user->id, $result[0]->user->id);
        $this->assertEquals($logs[0]->user->name, $result[0]->user->name);
        $this->assertEquals($logs[0]->user->email, $result[0]->user->email);
        $this->assertEquals($logs[0]->user->hashid, $result[0]->user->hashid);
        $this->assertEquals($logs[0]->icon, $result[0]->icon);
        $this->assertEquals($logs[0]->color, $result[0]->color);
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
