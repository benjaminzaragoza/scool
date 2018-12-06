<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class MoodleUsersPasswordControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class MoodleUsersPasswordControllerTest extends BaseTenantTest {

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
    public function superadmin_can_change_moodle_user_password()
    {
        $this->loginAsSuperAdmin('api');
        $moodleuser = create_sample_moodle_user();
        $response =  $this->json('PUT','/api/v1/moodle/users/' . $moodleuser->id . '/password', [
            'topsecret'
        ]);
        $response->assertSuccessful();
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function superadmin_can_change_moodle_user_password_validation()
    {
        $this->loginAsSuperAdmin('api');
        $moodleuser = create_sample_moodle_user();
        $response =  $this->json('PUT','/api/v1/moodle/users/' . $moodleuser->id . '/password' );
        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function users_manager_can_change_moodle_user_password()
    {
        $this->loginAsUsersManager('api');
        $moodleuser = create_sample_moodle_user();
        $response =  $this->json('PUT','/api/v1/moodle/users/' . $moodleuser->id . '/password', [
            'password' => 'topsecret'
        ]);
        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function moodle_manager_can_change_moodle_user_password()
    {
        $this->loginAsMoodleManager('api');
        $moodleuser = create_sample_moodle_user();
        $response =  $this->json('PUT','/api/v1/moodle/users/' . $moodleuser->id . '/password', [
            'password' => 'topsecret'
        ]);
        $response->assertSuccessful();
    }

    /**
     * @test
     */
    public function regular_user_cannot_change_moodle_user_password()
    {
        $this->login('api');
        $moodleuser = create_sample_moodle_user();
        $response =  $this->json('PUT','/api/v1/moodle/users/' . $moodleuser->id . '/password', [
            'topsecret'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_user_cannot_change_moodle_user_password()
    {
        $moodleuser = create_sample_moodle_user();
        $response =  $this->json('PUT','/api/v1/moodle/users/' . $moodleuser->id . '/password', [
            'topsecret'
        ]);
        $response->assertStatus(401);
    }
}
