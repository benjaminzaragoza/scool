<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Mail\Incidents\IncidentCommentAdded;
use App\Models\Incident;
use App\Models\User;
use App\Models\Reply;
use Config;
use Mail;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class IncidentRepliesControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class IncidentRepliesControllerTest extends BaseTenantTest
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

    protected function createIncident()
    {
        return Incident::create([
            'subject' => 'No funciona res a la Sala Mestral',
            'description' => 'Bla bla bla',
        ]);
    }

    /**
     * @param $incident
     */
    protected function addRepliesToIncident($incident)
    {
        $user = factory(User::class)->create();
        $reply1 = Reply::create([
            'body' => 'Si us plau podeu detallar una mica més el problema?',
            'user_id' => $user->id
        ]);
        $user2 = factory(User::class)->create();

        $reply2 = Reply::create([
            'body' => 'En realitat només falla la llum',
            'user_id' => $user2->id
        ]);
        $reply3 = Reply::create([
            'body' => 'Tanquem doncs la incidència, ja ha tornat la llum',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply1);
        $incident->addReply($reply2);
        $incident->addReply($reply3);
    }

    /**
     * @return mixed
     */
    protected function prepareIncidentWithReplies()
    {
        $incident = $this->createIncident();
        $incidentUser = factory(User::class)->create();
        $incident->assignUser($incidentUser);
        $this->addRepliesToIncident($incident);
        return $incident;
    }

    protected function createUserWithRoleIncidents()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        return $user;
    }

    protected function createUserWithRoleIncidentsManager()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        return $user;
    }

    /**
     * @test
     */
    public function an_incident_can_have_replies()
    {
        $incident =  $this->prepareIncidentWithReplies();

        $user = $this->createUserWithRoleIncidents();
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/incidents/' . $incident->id . '/replies');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Si us plau podeu detallar una mica més el problema?', $result[0]->body);
        $this->assertEquals( 'En realitat només falla la llum', $result[1]->body);
        $this->assertEquals('Tanquem doncs la incidència, ja ha tornat la llum', $result[2]->body);
    }

    /**
     * @test
     */
    public function regular_user_cannot_access_to_incident_replies()
    {
        $incident =  $this->prepareIncidentWithReplies();
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/incidents/' . $incident->id . '/replies');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function regular_user_cannot_access_to_incident_replies_if_incident_does_not_exists()
    {
        $response = $this->json('GET','/api/v1/incidents/1/replies');
        $response->assertStatus(404);
    }

    /**
     * @test
     */
    public function logged_user_can_add_a_reply_to_an_incident()
    {
        $incident = $this->createIncident();
        $user = $this->createUserWithRoleIncidents();
        $this->actingAs($user,'api');
        Mail::fake();
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        $response = $this->json('POST','/api/v1/incidents/' . $incident->id . '/replies',[
            'body' => 'Ja us hem resolt la incidència.'
        ]);
        $response->assertSuccessful();
        Mail::assertQueued(IncidentCommentAdded::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('Ja us hem resolt la incidència.', $result->body);
        $this->assertEquals( $user->id, $result->user_id);
        $this->assertEquals($user->name, $result->user_name);
        $this->assertEquals($user->email, $result->user_email);
    }

    /**
     * @test
     */
    public function incidents_manager_can_add_a_reply_to_an_incident()
    {
        $incident = $this->createIncident();
        $user = $this->createUserWithRoleIncidentsManager();
        $this->actingAs($user,'api');
        Mail::fake();
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        $response = $this->json('POST','/api/v1/incidents/' . $incident->id . '/replies',[
            'body' => 'Ja us hem resolt la incidència.'
        ]);
        $response->assertSuccessful();
        Mail::assertQueued(IncidentCommentAdded::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('Ja us hem resolt la incidència.', $result->body);
        $this->assertEquals( $user->id, $result->user_id);
        $this->assertEquals($user->name, $result->user_name);
        $this->assertEquals($user->email, $result->user_email);
    }

    /**
     * @test
     */
    public function incidents_manager_can_delete_a_reply()
    {
        $incident = $this->createIncident();
        $user = $this->createUserWithRoleIncidentsManager();
        $incident->addComment($reply=Reply::create(['body' => 'No funciona res', 'user_id' => $user->id]));
        $this->actingAs($user,'api');
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/replies/' . $reply->id);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $reply = $reply->fresh();
        $this->assertNull($reply);
        $this->assertEquals('No funciona res', $result->body);
        $this->assertEquals( $user->id, $result->user_id);
    }

    /**
     * @test
     */
    public function regular_user_cannot_delete_a_reply()
    {
        $incident = $this->createIncident();
        $user = factory(User::class)->create();
        $incident->addComment($reply=Reply::create(['body' => 'No funciona res', 'user_id' => $user->id]));
        $this->actingAs($user,'api');
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/replies/' . $reply->id);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function user_with_role_incidents_cannot_delete_a_reply()
    {
        $incident = $this->createIncident();
        $user = $this->createUserWithRoleIncidents();
        $incident->addComment($reply=Reply::create(['body' => 'No funciona res', 'user_id' => $user->id]));
        $this->actingAs($user,'api');
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/replies/' . $reply->id);

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function incidents_manager_can_update_a_reply_associated_to_an_incident()
    {
        $incident = $this->createIncident();
        $user = $this->createUserWithRoleIncidentsManager();
        $incident->addComment($reply=Reply::create(['body' => 'No funciona res', 'user_id' => $user->id]));
        $this->actingAs($user,'api');
        $response = $this->json('PUT','/api/v1/incidents/' . $incident->id . '/replies/' . $reply->id,[
            'body' => 'No funciona PC1 Aula 20'
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $reply = $reply->fresh();
        $this->assertEquals($reply->id, $result->id);
        $this->assertEquals('No funciona PC1 Aula 20', $result->body);
        $this->assertEquals( $user->id, $result->user_id);
    }

    /**
     * @test
     */
    public function incidents_manager_cannot_update_a_reply_not_associated_to_an_incident()
    {
        $incident = $this->createIncident();
        $user = $this->createUserWithRoleIncidentsManager();
        $reply=Reply::create(['body' => 'No funciona res', 'user_id' => $user->id]);
        $this->actingAs($user,'api');
        $response = $this->json('PUT','/api/v1/incidents/' . $incident->id . '/replies/' . $reply->id,[
            'body' => 'No funciona PC1 Aula 20'
        ]);
        $response->assertStatus(403);
    }
}
