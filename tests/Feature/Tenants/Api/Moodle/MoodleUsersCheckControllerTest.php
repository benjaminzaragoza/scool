<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Models\User;
use App\Moodle\Entities\MoodleUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class MoodleUsersCheckControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class MoodleUsersCheckControllerTest extends BaseTenantTest {

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
     * @test
     */
    public function superadmin_can_check_moodle_users_existing_uid()
    {
        $this->loginAsSuperAdmin('api');
        $user = create_sample_moodle_user();
        $response =  $this->json('POST','/api/v1/moodle/users/check', [
            'user' => [
                'id' => 1,
                'corporativeEmail' => 'prova@iesebre.com',
                'email' => 'emailpersonal@prova.com',
                'name' => 'Pepe Pardo Jeans'
            ]
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($result->status,'Error');
        $this->assertEquals($result->message[0],"S'han trobat usuari/s amb idnumber coincident");
        $this->assertCount(1,$result->users);
    }

    /**
     * @test
     */
    public function superadmin_can_check_moodle_users_existing_email()
    {
        $this->loginAsSuperAdmin('api');
        $user = create_sample_moodle_user();
        $response =  $this->json('POST','/api/v1/moodle/users/check', [
            'user' => [
                'id' => 9934945,
                'corporativeEmail' => 'prova@iesebre.com',
                'email' => 'maria_94_sr@hotmail.com',
                'name' => 'Pepe Pardo Jeans'
            ]
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($result->status,'Error');
        $this->assertEquals($result->message[0],"S'han trobat usuari/s amb email coincident");
        $this->assertCount(1,$result->users);
    }

    /**
     * @test
     */
    public function moodle_manager_can_check_moodle_users_existing_uid()
    {
        $this->loginAsMoodleManager('api');
        $user = create_sample_moodle_user();
        $response =  $this->json('POST','/api/v1/moodle/users/check', [
            'user' => [
                'id' => 1,
                'corporativeEmail' => 'prova@iesebre.com',
                'email' => 'emailpersonal@prova.com',
                'name' => 'Pepe Pardo Jeans'
            ]
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($result->status,'Error');
        $this->assertEquals($result->message[0],"S'han trobat usuari/s amb idnumber coincident");
        $this->assertCount(1,$result->users);
    }

    /**
     * @test
     */
    public function users_manager_can_check_moodle_users_existing_uid()
    {
        $this->loginAsUsersManager('api');
        $user = create_sample_moodle_user();
        $response =  $this->json('POST','/api/v1/moodle/users/check', [
            'user' => [
                'id' => 1,
                'corporativeEmail' => 'prova@iesebre.com',
                'email' => 'emailpersonal@prova.com',
                'name' => 'Pepe Pardo Jeans'
            ]
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($result->status,'Error');
        $this->assertEquals($result->message[0],"S'han trobat usuari/s amb idnumber coincident");
        $this->assertCount(1,$result->users);
    }

    /**
     * @test
     */
    public function regular_user_cannot_check_moodle_users()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $response =  $this->json('POST','/api/v1/moodle/users/check');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_user_cannot_check_moodle_users()
    {
        $response =  $this->json('POST','/api/v1/moodle/users/check');
        $response->assertStatus(401);
    }

}
