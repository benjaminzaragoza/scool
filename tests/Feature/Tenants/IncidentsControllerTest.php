<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
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

        $response =  $this->json('POST','/api/v1/incidents',$incident = [
            'subject' => 'Ordinador Aula 36 no funcion',
            'description' => "El ordinador de l'aula 36 bla bla la"
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('incidents',$incident);

        $user = $user->fresh();

        $this->assertCount(1,$user->incidents);
        dd($user->incidents->first());

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
    public function regular_user_cannot_set_incidences()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response =  $this->json('POST','/api/v1/incidents',$incident = [
            'subject' => 'Ordinador Aula 36 no funcion',
            'description' => "El ordinador de l'aula 36 bla bla la"
        ]);
        $response->assertStatus(403);
    }
}