<?php

namespace Tests\Feature\Tenants\Api;

use App\Models\Incident;
use App\Models\User;
use App\Models\Reply;
use Config;
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

    /**
     * @test
     */
    public function an_incident_can_have_replies()
    {
        $incident = Incident::create([
            'subject' => 'No funciona res a la Sala Mestral',
            'description' => 'Bla bla bla',
        ]);
        $incidentUser = factory(User::class)->create();
        $incident->assignUser($incidentUser);
        $reply1 = Reply::create([
            'body' => 'Si us plau podeu detallar una mica més el problema?'
        ]);
        $reply2 = Reply::create([
            'body' => 'En realitat només falla la llum'
        ]);
        $reply3 = Reply::create([
            'body' => 'Tanquem doncs la incidència, ja ha tornat la llum'
        ]);
        $incident->addReply($reply1);
        $incident->addReply($reply2);
        $incident->addReply($reply3);

        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/incidents/' . $incident->id . '/replies');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Si us plau podeu detallar una mica més el problema?', $result[0]->body);
        $this->assertEquals( 'En realitat només falla la llum', $result[1]->body);
        $this->assertEquals('Tanquem doncs la incidència, ja ha tornat la llum', $result[2]->body);
    }
}
