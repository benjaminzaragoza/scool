<?php

namespace Tests\Feature\Tenants\Api\People;

use App\Models\Person;
use App\Models\User;
use Config;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class PeopleControllerTest.
 *
 * @package Tests\Feature
 */
class PeopleControllerTest extends BaseTenantTest
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


    /** @test */
    public function user_manager_can_update_people()
    {
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $person = Person::create([
            'givenName' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeana'
        ]);
        $response = $this->json('PUT','/api/v1/people/' . $person->id,[
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);

        $response->assertSuccessful();
        $person = $person->fresh();
        $result = json_decode($response->getContent());
        dump($result);
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
    }

    /** @test */
    public function guest_user_cannot_update_people()
    {
        $person = Person::create([
            'givenName' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeana'
        ]);
        $response = $this->json('PUT','/api/v1/people/' . $person->id,[
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);

        $response->assertRedirect('/login');
    }

}
