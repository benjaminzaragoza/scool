<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleUsersSearchControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleUsersSearchControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group slow
     * @group google
     */
    public function search_google_user()
    {
        $this->withoutExceptionHandling();
        config_google_api();
        tune_google_client();
        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->json('POST','/api/v1/gsuite/users/search',[
            'employeeId' => 11,
            'personalEmail' => 'sdas@dsas.es',
            'mobile' =>  '123456789',
            'name' =>  'Pepe Pardo Jeans'
        ]);

        $response->assertSuccessful();

        $user = json_decode($response->getContent());
        dd($user);
    }

    /**
     * @test
     * @group google
     */
    public function regular_user_cannot_search_user()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/gsuite/users/search');

        $response->assertStatus(403);
    }
}
