<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Models\IncidentTag;
use App\Models\User;
use Config;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class TagsControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class TagsControllerTest extends BaseTenantTest{

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
    public function can_show_incident_tags()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $tag= IncidentTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('GET','/api/v1/incidents/tags/' . $tag->id);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }

    /**
     * @test
     */
    public function regular_user_cannot_show_incident_tags()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $tag= IncidentTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('GET','/api/v1/incidents/tags/' . $tag->id);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function can_list_incident_tags()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $tag1 = IncidentTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $tag2 = IncidentTag::create([
            'value' => 'tag2',
            'description' => 'description2',
            'color' => '#456545'
        ]);

        $tag3 = IncidentTag::create([
            'value' => 'tag3',
            'description' => 'description3',
            'color' => '#453445'
        ]);

        $tags = [ $tag1, $tag2, $tag3];

        $response = $this->json('GET','/api/v1/incidents/tags');
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertEquals($tags[0]->id, $result[0]->id);
        $this->assertEquals($tags[0]->value, $result[0]->value);
        $this->assertEquals($tags[0]->description, $result[0]->description);
        $this->assertEquals($tags[1]->color, $result[1]->color);
        $this->assertEquals($tags[1]->id, $result[1]->id);
        $this->assertEquals($tags[1]->value, $result[1]->value);
        $this->assertEquals($tags[1]->description, $result[1]->description);
        $this->assertEquals($tags[1]->color, $result[1]->color);
        $this->assertEquals($tags[2]->color, $result[2]->color);
        $this->assertEquals($tags[2]->id, $result[2]->id);
        $this->assertEquals($tags[2]->value, $result[2]->value);
        $this->assertEquals($tags[2]->description, $result[2]->description);
        $this->assertEquals($tags[2]->color, $result[2]->color);
    }


    /**
     * @test
     */
    public function regular_user_cannot_list_incident_tags()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/incidents/tags');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function can_store_incident_tag()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/incidents/tags',$tag = [
            'value' => 'wontfix',
            'description' => 'No es vol resoldre la incidència',
            'color' => '#342334'
        ]);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertNotNull($result->id);
        $this->assertEquals($tag['value'], $result->value);
        $this->assertEquals($tag['description'], $result->description);
        $this->assertEquals($tag['color'], $result->color);
    }

    /**
     * @test
     */
    public function regular_user_cannot_store_incident_tag()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/incidents/tags',$tag = [
            'value' => 'wontfix',
            'description' => 'No es vol resoldre la incidència',
            'color' => '#342334'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function incident_user_cannot_store_incident_tag()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/incidents/tags',$tag = [
            'value' => 'wontfix',
            'description' => 'No es vol resoldre la incidència',
            'color' => '#342334'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function store_incident_tag_validation()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/incidents/tags',[]);
        $response->assertStatus(422);
    }

    /** @test */
    public function can_update_incident_tag()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $tag= IncidentTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('PUT','/api/v1/incidents/tags/' . $tag->id, [
            'value' => 'newwontfix',
            'description' => 'description',
            'color' => '#111111'
        ]);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $tag = $tag->refresh();
        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }

    /** @test */
    public function regular_user_cannot_update_incident_tag()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $tag= IncidentTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('PUT','/api/v1/incidents/tags/' . $tag->id, [
            'value' => 'newwontfix',
            'description' => 'description',
            'color' => '#111111'
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function incidents_user_cannot_update_incident_tag()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $tag= IncidentTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('PUT','/api/v1/incidents/tags/' . $tag->id, [
            'value' => 'newwontfix',
            'description' => 'description',
            'color' => '#111111'
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function can_destroy_incident_tag()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'IncidentsManager']);
        Config::set('auth.providers.users.model', User::class);
        $user->assignRole($role);
        $this->actingAs($user,'api');

        $tag= IncidentTag::create([
            'value' => 'wontfix',
            'description' => 'No és vol o no es pot resoldre',
            'color' => '#456578'
        ]);

        $response = $this->json('DELETE','/api/v1/incidents/tags/' . $tag->id);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertNull(IncidentTag::find($tag->id));
        $this->assertEquals($tag->id, $result->id);
        $this->assertEquals($tag->value, $result->value);
        $this->assertEquals($tag->description, $result->description);
        $this->assertEquals($tag->color, $result->color);
    }
}
