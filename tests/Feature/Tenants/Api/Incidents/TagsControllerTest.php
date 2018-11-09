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
 * Class IncidentTagsControllerTest.
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
        $this->withoutExceptionHandling();
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

}
