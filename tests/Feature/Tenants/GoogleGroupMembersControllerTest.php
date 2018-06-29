<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleGroupMembersControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleGroupMembersControllerTest extends BaseTenantTest
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
     * List groups.
     *
     * @test
     * @group slow
     */
    public function list_members()
    {
        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);
        // Claustre group id: 01mrcu094b1d570
        $response = $this->json('GET','/api/v1/gsuite/groups/01mrcu094b1d570/members');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertTrue(is_array($result));
        $this->assertTrue(google_groups_check_member($result[0]));
    }

    /**
     *
     * @test
     * @group slow
     */
    public function regular_user_cannot_list_members()
    {
        config_google_api();

        $user = create(User::class);
        $this->actingAs($user,'api');
        $response = $this->json('GET','/api/v1/gsuite/groups');
        $response->assertStatus(403);
    }

}
