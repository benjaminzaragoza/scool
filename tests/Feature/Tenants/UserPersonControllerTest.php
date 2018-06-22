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
        $this->withoutExceptionHandling();
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
            'email' =>'pepepardo@jeans.com'
        ]);

        $response->assertSuccessful();

        $result = json_decode($response->getContent());

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
}
