<?php

namespace Tests\Feature\Tenants\Api\Google;

use App\Models\GoogleUser;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class GoogleUsersControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleUsersControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group google
     * @group slow
     */
    public function delete_multipe_user()
    {
        config_google_api();
        tune_google_client();

        $this->loginAsUsersManager('api');

        google_user_remove('provaborrar777@iesebre.com');
        google_user_remove('provaborrar888@iesebre.com');
        try {
            google_user_create([
                'givenName' =>  'Nom',
                'familyName' =>  'Cognom',
                'primaryEmail' =>  'provaborrar777@iesebre.com'
            ]);
            google_user_create([
                'givenName' =>  'Nom2',
                'familyName' =>  'Cognom2',
                'primaryEmail' =>  'provaborrar888@iesebre.com'
            ]);
        } catch (\Exception $e) {

        }
        sleep(5);
        $user1 = google_user_get('provaborrar777@iesebre.com');
        $user2 = google_user_get('provaborrar888@iesebre.com');

//        dump($user1->id);
//        dd($user2->id);

        $response = $this->json('POST','/api/v1/gsuite/users/multiple', [
            'users' => [ $user1->id , $user2->id]
        ]);
        sleep(5);

        $response->assertSuccessful();
        $this->assertFalse(google_user_exists('provaborrar777@iesebre.com'));
        $this->assertFalse(google_user_exists('provaborrar888@iesebre.com'));
    }

    /**
     * @test
     * @group google
     *
     */
    public function delete_multipe_user_validation()
    {
        $this->loginAsUsersManager('api');
        $response = $this->json('POST','/api/v1/gsuite/users/multiple', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group google
     *
     */
    public function regular_user_cannot_delete_multipe_user()
    {
        $this->login('api');
        $response = $this->json('POST','/api/v1/gsuite/users/multiple', [1,2]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group google
     *
     */
    public function guest_user_cannot_delete_multipe_user()
    {
        $response = $this->json('POST','/api/v1/gsuite/users/multiple', [1,2]);
        $response->assertStatus(401);
    }
}
