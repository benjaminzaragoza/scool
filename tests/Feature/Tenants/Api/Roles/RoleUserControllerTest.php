<?php

namespace Tests\Feature\Tenant\Api\Roles;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class RoleUserControllerTest.
 *
 * @package Tests\Feature
 */
class RoleUserControllerTest extends BaseTenantTest
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
    public function can_show_users_with_an_specific_role()
    {
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'ProvaRol']);

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();

        $user1->assignRole($role);
        $user2->assignRole($role);
        $user3->assignRole($role);

        $this->assertFalse($user->hasRole($role));

        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/role/' . $role->id . '/users' );

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3,$result);
        $this->assertEquals($user1->id,$result[0]->id);
        $this->assertEquals($user2->id,$result[1]->id);
        $this->assertEquals($user3->id,$result[2]->id);
    }

    /** @test */
    public function can_show_users_with_an_specific_role_name()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'ProvaRol']);

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user3 = factory(User::class)->create();

        $user1->assignRole($role);
        $user2->assignRole($role);
        $user3->assignRole($role);

        $this->assertFalse($user->hasRole($role));

        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/role/' . $role->name . '/users' );

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3,$result);
        $this->assertEquals($user1->id,$result[0]->id);
        $this->assertEquals($user2->id,$result[1]->id);
        $this->assertEquals($user3->id,$result[2]->id);
    }

}
