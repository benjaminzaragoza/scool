<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleUsersControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleUsersControllerTest extends BaseTenantTest
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
     * @ test
     * @group slow
     */
    public function show_google_users()
    {
        config_google_api();
        tune_google_client();
        $usersManager = create(User::class);
        $this->actingAs($usersManager);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->get('google_users');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.google_users.show');
        $response->assertViewHas('users', function($users) {
            return google_user_check($users[0]);
        });
    }

    /** @test */
    public function regular_user_cannot_show_google_users()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('google_users');

        $response->assertStatus(403);
    }

    /**
     * List google users.
     *
     * @test
     * @group slow
     * @group google
     */
    public function list_google_users()
    {
        config_google_api();
        tune_google_client();
        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->json('GET','/api/v1/gsuite/users');

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertTrue(is_array($result));
        $this->assertTrue(google_user_check($result[0]));
    }

    /**
     * @test
     * @group google
     */
    public function regular_user_cannot_list_users()
    {
        config_google_api();
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/gsuite/users');

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group slow
     * @group google
     */
    public function create_user()
    {
        config_google_api();
        config_google_api();
        tune_google_client();

        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        try {
            google_user_remove('provauser123@iesebre.com');
        } catch (\Exception $e) {

        }

        sleep(5);
        $response = $this->json('POST','/api/v1/gsuite/users', [
            'givenName' => 'prova',
            'familyName' => '123',
            'primaryEmail' => 'provauser123@iesebre.com'
        ]);

        $response->assertSuccessful();
        sleep(5);
        $this->assertTrue(google_user_exists('provauser123@iesebre.com'));
    }

    /**
     * @test
     * @group slow
     * @group google
     */
    public function create_user_with_user_id()
    {
        config_google_api();
        config_google_api();
        tune_google_client();

        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        try {
            google_user_remove('provauser123@iesebre.com');
        } catch (\Exception $e) {

        }

        sleep(5);
        $response = $this->json('POST','/api/v1/gsuite/users', [
            'id' => 454545,
            'givenName' => 'prova',
            'familyName' => '123',
            'primaryEmail' => 'provauser123@iesebre.com'
        ]);

        $response->assertSuccessful();
        sleep(5);
        $this->assertTrue(google_user_exists('provauser123@iesebre.com'));

        $user = google_user_get('provauser123@iesebre.com');
        $this->assertEquals(454545,$user->externalIds[0]['value']);
    }

    /**
     * @test
     * @group google
     */
    public function create_user_validation()
    {
        config_google_api();
        tune_google_client();

        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->json('POST','/api/v1/gsuite/users', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group google
     */
    public function regular_user_cannot_create_user()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/gsuite/users');

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group google
     * @group slow
     */
    public function delete_user()
    {
        config_google_api();
        tune_google_client();

        $usersManager = create(User::class);
        $this->actingAs($usersManager,'api');
        $role = Role::firstOrCreate(['name' => 'UsersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        try {
            google_user_create([
               'givenName' =>  'Nom',
               'familyName' =>  'Cognom',
               'primaryEmail' =>  'provaborrar777@iesebre.com'
            ]);
        } catch (\Exception $e) {

        }
        sleep(5);
        $user = google_user_get('provaborrar777@iesebre.com');
        $response = $this->json('DELETE','/api/v1/gsuite/users/' . $user->id);

        $response->assertSuccessful();
        $this->assertFalse(google_user_exists('provaborrar777@iesebre.com'));
    }

    /**
     * @test
     * @group google
     */
    public function regular_user_cannot_delete_user()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('DELETE','/api/v1/gsuite/users/12311');

        $response->assertStatus(403);
    }
}
