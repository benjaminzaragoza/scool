<?php

namespace Tests\Feature\Tenants\Api\People;

use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PeopleControllerTest.
 *
 * @package Tests\Feature
 */
class PeopleControllerTest extends BaseTenantTest
{
    use RefreshDatabase, CanLogin;

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
    public function superadmin_can_store_people()
    {
        $this->loginAsSuperAdmin('api');
        $user = factory(User::class)->create();
        $person = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'user_id' => $user->id
        ];
        $response = $this->json('POST','/api/v1/people/',$person);
        $response->assertSuccessful();
        $person = Person::first();
        $result = json_decode($response->getContent());
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($person->user_id,$user->id);
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
        $this->assertEquals($result->user_id,$user->id);
    }

    /** @test */
    public function superadmin_can_store_people_ucfirst()
    {
        $this->loginAsSuperAdmin('api');
        $user = factory(User::class)->create();
        $person = [
            'givenName' => 'pepe',
            'sn1' => 'pardo',
            'sn2' => 'jeans',
            'user_id' => $user->id
        ];
        $response = $this->json('POST','/api/v1/people/',$person);
        $response->assertSuccessful();
        $person = Person::first();
        $result = json_decode($response->getContent());
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($person->user_id,$user->id);
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
        $this->assertEquals($result->user_id,$user->id);
    }

    /** @test */
    public function superadmin_can_store_people_validation()
    {
        $this->loginAsSuperAdmin('api');

        $person = [];
        $response = $this->json('POST','/api/v1/people/',$person);
        $response->assertStatus(422);
    }

    /** @test */
    public function user_manager_can_store_people()
    {
        $this->loginAsUsersManager('api');

        $user = factory(User::class)->create();
        $person = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'user_id' => $user->id
        ];
        $response = $this->json('POST','/api/v1/people/',$person);
        $response->assertSuccessful();
        $person = Person::first();
        $result = json_decode($response->getContent());
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($person->user_id,$user->id);
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
        $this->assertEquals($result->user_id,$user->id);
    }

    /** @test */
    public function people_manager_can_store_people()
    {
        $this->loginAsPeopleManager('api');

        $user = factory(User::class)->create();
        $person = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'user_id' => $user->id
        ];
        $response = $this->json('POST','/api/v1/people/',$person);
        $response->assertSuccessful();
        $person = Person::first();
        $result = json_decode($response->getContent());
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($person->user_id,$user->id);
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
        $this->assertEquals($result->user_id,$user->id);
    }

    /** @test */
    public function moodle_manager_can_store_people()
    {
        $this->loginAsMoodleManager('api');

        $user = factory(User::class)->create();
        $person = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'user_id' => $user->id
        ];
        $response = $this->json('POST','/api/v1/people/',$person);
        $response->assertSuccessful();
        $person = Person::first();
        $result = json_decode($response->getContent());
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($person->user_id,$user->id);
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
        $this->assertEquals($result->user_id,$user->id);
    }

    /** @test */
    public function regular_user_cannot_store_people()
    {
        $this->login('api');
        $person = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ];
        $response = $this->json('POST','/api/v1/people/',$person);
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_store_people()
    {
        $person = [
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ];
        $response = $this->json('POST','/api/v1/people/',$person);
        $response->assertStatus(401);
    }

    /** @test */
    public function superadmin_can_update_people()
    {
        $this->loginAsSuperAdmin('api');

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
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
    }

    /** @test */
    public function superadmin_can_update_people_validation()
    {
        $this->loginAsSuperAdmin('api');

        $person = Person::create([
            'givenName' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeana'
        ]);
        $response = $this->json('PUT','/api/v1/people/' . $person->id,[]);

        $response->assertStatus(422);
    }

    /** @test */
    public function users_manager_can_update_people()
    {
        $this->loginAsUsersManager('api');

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
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
    }

    /** @test */
    public function people_manager_can_update_people()
    {
        $this->loginAsPeopleManager('api');

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
        $this->assertEquals($person->givenName,'Pepe');
        $this->assertEquals($person->sn1,'Pardo');
        $this->assertEquals($person->sn2,'Jeans');
        $this->assertEquals($result->givenName,'Pepe');
        $this->assertEquals($result->sn1,'Pardo');
        $this->assertEquals($result->sn2,'Jeans');
    }

    /** @test */
    public function regular_user_cannot_update_people()
    {
        $this->login('api');
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
        $response->assertStatus(403);
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
        $response->assertStatus(401);
    }

}
