<?php

namespace Tests\Feature;

use App\Models\User;
use Config;
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
        dump('/api/v1/role/name/' . $role->name);
        $response = $this->json('GET','/api/v1/role/name/' . $role->name );
        $response->assertSuccessful();
        dd($response->getContent());
    }

}
