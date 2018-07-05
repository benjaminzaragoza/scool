<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleGroupsControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleGroupsControllerTest extends BaseTenantTest
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
     * @group google
     */
    public function show_google_groups()
    {
        config_google_api();

        $usersManager = create(User::class);
        $this->actingAs($usersManager);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->get('google_groups');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.google_groups.show');
        $response->assertViewHas('groups', function($groups) {
            return google_group_check($groups[0]);
        });
    }

    /** @test
     *  @group google
     */
    public function regular_user_cannot_show_google_groups()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('google_groups');

        $response->assertStatus(403);
    }

    /**
     * List groups.
     *
     * @test
     * @group slow
     * @group google
     */
    public function list_groups()
    {
        config_google_api();

        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);
        $response = $this->json('GET','/api/v1/gsuite/groups');
        $response->assertSuccessful();
        $groups = json_decode($response->getContent());
//        dd($groups[0]);
        $this->assertTrue(google_group_check($groups[0]));
    }

    /**
     *
     * @test
     * @group slow
     * @group google
     */
    public function regular_user_cannot_list_groups()
    {
        config_google_api();

        $user = create(User::class);
        $this->actingAs($user,'api');
        $response = $this->json('GET','/api/v1/gsuite/groups');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group slow
     * @group google
     */
    public function create_group()
    {
        config_google_api();

        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);
        google_group_remove('prova123@iesebre.com');
        sleep(5);
        $response = $this->json('POST','/api/v1/gsuite/groups', [
            'name' => 'Grup de prova',
            'email' => 'prova123@iesebre.com',
            'description' => 'Prova de descripció'
        ]);

        $response->assertSuccessful();
        sleep(5);
        $this->assertTrue(google_group_exists('prova123@iesebre.com'));
    }

    /**
     * @test
     * @group google
     */
    public function create_group_validation()
    {
        config_google_api();

        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->json('POST','/api/v1/gsuite/groups', []);

        $response->assertStatus(422);
    }

    /**
     * @test
     * @group google
     */
    public function regular_user_cannot_create_group()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/gsuite/groups');

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group google
     * @group slow
     */
    public function delete_group()
    {
        config_google_api();

        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        try {
            google_group_create('provaborrar777@iesebre.com');
        } catch (\Exception $e) {

        }
        sleep(5);
        $group = google_group_get('provaborrar777@iesebre.com');
        $response = $this->json('DELETE','/api/v1/gsuite/groups/' . $group->id);

        $response->assertSuccessful();
        sleep(5);

        google_group_remove('provaborrar777@iesebre.com');

    }

    /**
     * @test
     * @group google
     */
    public function regular_user_cannot_delete_group()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('DELETE','/api/v1/gsuite/groups/12311');

        $response->assertStatus(403);
    }
}
