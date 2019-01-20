<?php

namespace Tests\Feature\Tenants\Api\Users;

use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use App\Models\Role as ScoolRole;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserPersonControllerTest.
 *
 * @package Tests\Feature
 */
class UserPersonControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group users
     */
    public function user_manager_can_add_user_persons()
    {
        $this->loginAsUsersManager('api');

        $response = $this->json('POST','/api/v1/user_person',[
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'email' =>'pepepardo@jeans.com',
            'user_type_id' => UserType::TEACHER,
            'role' => 'UsersManager'
        ]);

        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertNotNull($result->id);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('Pepe',$result->givenName);
        $this->assertEquals('Pardo',$result->sn1);
        $this->assertEquals('Jeans',$result->sn2);
        $this->assertEquals('pepepardo@jeans.com',$result->email);
        $this->assertEquals('Ay',$result->hash_id);

        $user = User::findByName('Pepe Pardo Jeans');
        $this->assertNotNull($user);
        $this->assertEquals('pepepardo@jeans.com',$user->email);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $person = Person::where('user_id',$user->id)->first();
        $this->assertEquals('Pepe',$person->givenName);
        $this->assertEquals('Pardo',$person->sn1);
        $this->assertEquals('Jeans',$person->sn2);

        $this->assertTrue($user->hasRole('UsersManager'));
    }

    /**
     * @test
     * @group users
     */
    public function user_manager_can_add_teacher()
    {
        initialize_teacher_role();
        $this->loginAsUsersManager('api');
        $response = $this->json('POST','/api/v1/user_person',[
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'email' =>'pepepardo@jeans.com',
            'user_type_id' => UserType::TEACHER,
        ]);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertNotNull($result->id);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('Pepe',$result->givenName);
        $this->assertEquals('Pardo',$result->sn1);
        $this->assertEquals('Jeans',$result->sn2);
        $this->assertEquals('pepepardo@jeans.com',$result->email);
        $this->assertEquals('Ay',$result->hash_id);

        $user = User::findByName('Pepe Pardo Jeans');
        $this->assertNotNull($user);
        $this->assertEquals('pepepardo@jeans.com',$user->email);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $person = Person::where('user_id',$user->id)->first();
        $this->assertEquals('Pepe',$person->givenName);
        $this->assertEquals('Pardo',$person->sn1);
        $this->assertEquals('Jeans',$person->sn2);
        $this->assertTrue($user->hasRole(ScoolRole::TEACHER['name']));
    }

    /**
     * @test
     * @group users
     */
    public function user_manager_can_add_student()
    {
        initialize_student_role();
        $this->loginAsUsersManager('api');
        $response = $this->json('POST','/api/v1/user_person',[
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'email' =>'pepepardo@jeans.com',
            'user_type_id' => UserType::STUDENT,
        ]);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertNotNull($result->id);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('Pepe',$result->givenName);
        $this->assertEquals('Pardo',$result->sn1);
        $this->assertEquals('Jeans',$result->sn2);
        $this->assertEquals('pepepardo@jeans.com',$result->email);
        $this->assertEquals('Ay',$result->hash_id);

        $user = User::findByName('Pepe Pardo Jeans');
        $this->assertNotNull($user);
        $this->assertEquals('pepepardo@jeans.com',$user->email);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $person = Person::where('user_id',$user->id)->first();
        $this->assertEquals('Pepe',$person->givenName);
        $this->assertEquals('Pardo',$person->sn1);
        $this->assertEquals('Jeans',$person->sn2);
        $this->assertTrue($user->hasRole(ScoolRole::STUDENT['name']));
    }

    /**
     * @test
     * @group users
     */
    public function user_manager_can_add_user_persons_ucfirst()
    {
        $this->loginAsUsersManager('api');

        $response = $this->json('POST','/api/v1/user_person',[
            'givenName' => 'pepe',
            'sn1' => 'pardo',
            'sn2' => 'jeans ',
            'email' =>'pepepardo@jeans.com',
            'user_type_id' => 1,
            'role' => 'UsersManager'
        ]);

        $response->assertSuccessful();

        $result = json_decode($response->getContent());

        $this->assertNotNull($result->id);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('Pepe',$result->givenName);
        $this->assertEquals('Pardo',$result->sn1);
        $this->assertEquals('Jeans',$result->sn2);
        $this->assertEquals('pepepardo@jeans.com',$result->email);

        $user = User::findByName('Pepe Pardo Jeans');
        $this->assertNotNull($user);
        $this->assertEquals('pepepardo@jeans.com',$user->email);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $person = Person::where('user_id',$user->id)->first();
        $this->assertEquals('Pepe',$person->givenName);
        $this->assertEquals('Pardo',$person->sn1);
        $this->assertEquals('Jeans',$person->sn2);

        $this->assertTrue($user->hasRole('UsersManager'));
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_add_user_persons()
    {
        $this->login('api');

        $response = $this->json('POST','/api/v1/user_person',[
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com'
        ]);

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_add_user_persons()
    {
        $response = $this->json('POST','/api/v1/user_person',[
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com'
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     * @group users
     */
    public function user_manager_can_delete_user_persons()
    {
        $this->loginAsUsersManager('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]);
        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);
        $person->assignUser($user->id);

        $response = $this->json('DELETE','/api/v1/user_person/' . $user->id);

        $response->assertSuccessful();

        $this->assertNull(User::findByName('Pepe Pardo Jeans'));
        $this->assertNull(User::find($user->id));
        $this->assertNull(Person::find($person->id));
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_delete_user_persons()
    {
        $this->login('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]);

        $response = $this->json('DELETE','/api/v1/user_person/' . $user->id);

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_delete_user_persons()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]);

        $response = $this->json('DELETE','/api/v1/user_person/' . $user->id);

        $response->assertStatus(401);
    }
}
