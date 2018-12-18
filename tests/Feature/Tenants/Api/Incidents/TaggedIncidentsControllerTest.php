<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Events\Incidents\IncidentTagAdded;
use App\Events\Incidents\IncidentTagRemoved;
use App\Models\Incident;
use App\Models\IncidentTag;
use App\Models\User;
use Config;
use Event;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class TaggedIncidentsControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class TaggedIncidentsControllerTest extends BaseTenantTest{

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
    public function can_add_tag_to_incident()
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
        $tag = IncidentTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);
        $this->assertCount(0,$incident->tags);
        Event::fake();
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        $response = $this->json('POST','/api/v1/incidents/' . $incident->id . '/tags/' . $tag-> id);
        $response->assertSuccessful();
        Event::assertDispatched(IncidentTagAdded::class,function ($event) use ($incident){
            return $event->incident->is($incident) && $event->tag->value === 'etiqueta 2';
        });
        $incident = $incident->fresh();
        $this->assertCount(1,$incident->tags);
        $this->assertTrue($incident->tags[0]->is($tag));
    }

    /** @test */
    public function incidents_users_cannot_add_tag_to_incident()
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
        $tag = IncidentTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);

        $this->assertCount(0,$incident->tags);
        $response = $this->json('POST','/api/v1/incidents/' . $incident->id . '/tags/' . $tag-> id);

        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_add_tag_to_incident()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $tag = IncidentTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);

        $this->assertCount(0,$incident->tags);
        $response = $this->json('POST','/api/v1/incidents/' . $incident->id . '/tags/' . $tag-> id);
        $response->assertStatus(403);
    }

    /** @test */
    public function can_remove_tag_from_incident()
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
        $tag = IncidentTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);
        $incident->addTag($tag);
        $this->assertCount(1,$incident->tags);
        Event::fake();
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/tags/' . $tag-> id);
        $response->assertSuccessful();
        Event::assertDispatched(IncidentTagRemoved::class,function ($event) use ($incident){
            return $event->incident->is($incident) && $event->oldTag->value === 'etiqueta 2';
        });
        $incident = $incident->fresh();
        $this->assertCount(0,$incident->tags);
    }

    /** @test */
    public function incidents_users_cannot_remove_tag_from_incident()
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
        $tag = IncidentTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);
        $incident->addTag($tag);
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/tags/' . $tag-> id);
        $response->assertStatus(403);
    }

    /** @test */
    public function regular_user_cannot_remove_tag_from_incident()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $incident = Incident::create([
            'subject' => 'No funciona Aula 36 pc 1',
            'description' => 'bla bla bla'
        ]);
        $tag = IncidentTag::create([
            'value' => 'etiqueta 2',
            'description' => 'Descripció etiqueta 2',
            'color' => 'blue',
            'icon' => 'settings'
        ]);
        $incident->addTag($tag);
        $this->assertCount(1,$incident->tags);
        $response = $this->json('DELETE','/api/v1/incidents/' . $incident->id . '/tags/' . $tag-> id);
        $response->assertStatus(403);
    }
}
