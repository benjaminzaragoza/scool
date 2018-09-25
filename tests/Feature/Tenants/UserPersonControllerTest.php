<?php

namespace Tests\Feature\Tenants;

use App\Models\Person;
use App\Models\User;
use Config;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class UserPersonControllerTest.
 *
 * @package Tests\Feature
 */
class UserPersonControllerTest extends BaseTenantTest
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
    public function user_manager_can_add_user_persons()
    {
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('POST','/api/v1/user_person',[
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
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

    /** @test */
    public function user_manager_can_add_user_persons_ucfirst()
    {
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

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

    /** @test */
    public function regular_user_cannot_add_user_persons()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/user_person',[
            'name' => 'Pepe Pardo',
            'email' =>'pepepardo@jeans.com'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_manager_can_delete_user_persons()
    {
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]);
        $person = Person::create([
            'user_id' => $user->id,
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);

        $response = $this->json('DELETE','/api/v1/user_person/' . $user->id);

        $response->assertSuccessful();

        $this->assertNull(User::findByName('Pepe Pardo Jeans'));
        $this->assertNull(User::find($user->id));
        $this->assertNull(Person::find($person->id));
    }

    /** @test */
    public function regular_user_cannot_delete_user_persons()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]);

        $response = $this->json('DELETE','/api/v1/user_person/' . $user->id);

        $response->assertStatus(403);
    }
}
