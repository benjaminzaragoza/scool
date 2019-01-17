<?php

namespace Tests\Feature\Tenant\Api\Roles;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserRoleNameControllerTest.
 *
 * @package Tests\Feature
 */
class UserRoleNameControllerTest extends BaseTenantTest
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
    public function can_get_role_by_name()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $role = Role::firstOrCreate(['name' => 'Incidents']);
        $response = $this->json('GET','/api/v1/role/name/' . $role->name );
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($role->id, $result->id);
        $this->assertEquals($role->name, $result->name);
        $this->assertEquals($role->guard_name, $result->guard_name);
    }

}
