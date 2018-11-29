<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Models\User;
use App\Moodle\Entities\MoodleUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class MoodleUsersControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class MoodleUsersControllerTest extends BaseTenantTest {

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
     * @group moodle
     * @group slow
     */
    public function superadmin_can_list_moodle_users()
    {
        $this->loginAsSuperAdmin('api');
        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertTrue(is_array($result));
        $this->assertNotNull($result[0]->id);
        $this->assertNotNull($result[0]->username);
        $this->assertNotNull($result[0]->email);
    }

    /**
     * @test
     */
    public function regular_user_cannot_list_moodle_users()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_user_cannot_list_moodle_users()
    {
        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function superadmin_can_destroy_moodle_users()
    {
        $this->loginAsSuperAdmin('api');
        $user = create_sample_moodle_user();
        $response =  $this->json('DELETE','/api/v1/moodle/users/' . $user->id);
        $response->assertSuccessful();
        $this->assertNull(MoodleUser::get($user->username));
    }

    /**
     * @test
     */
    public function regular_user_cannot_destroy_moodle_users()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $response =  $this->json('DELETE','/api/v1/moodle/users/1');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_user_cannot_destroy_moodle_users()
    {
        $response =  $this->json('DELETE','/api/v1/moodle/users/1');
        $response->assertStatus(401);
    }

}
