<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class GoogleUsersPasswordControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class GoogleUsersPasswordControllerTest extends BaseTenantTest {

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
     * @group google
     * @group slow
     */
    public function superadmin_can_change_google_user_password()
    {
        config_google_api();
        tune_google_client();
        $this->loginAsSuperAdmin('api');
        $googleuser = create_sample_google_user();
        $response =  $this->json('PUT','/api/v1/gsuite/users/' . $googleuser->primaryEmail . '/password', [
            'password' => 'topsecret'
        ]);
        $response->assertSuccessful();
        google_user_remove($googleuser->primaryEmail);
    }

    /**
     * @test
     * @group google
     * @group slow
     */
    public function users_manager_can_change_google_user_password()
    {
        config_google_api();
        tune_google_client();
        $this->loginAsUsersManager('api');
        $googleuser = create_sample_google_user();
        $response =  $this->json('PUT','/api/v1/gsuite/users/' . $googleuser->primaryEmail . '/password', [
            'password' => 'topsecret'
        ]);
        $response->assertSuccessful();
        google_user_remove($googleuser->primaryEmail);
    }

    /**
     * @test
     * @group google
     * @group slow
     */
    public function superadmin_can_change_google_user_password_validation()
    {
        $this->loginAsSuperAdmin('api');
        $response =  $this->json('PUT','/api/v1/gsuite/users/1051231434574456692166/password', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group google
     * @group slow
     */
    public function regular_user_cannot_change_google_user_password_validation()
    {
        $this->login('api');
        $response =  $this->json('PUT','/api/v1/gsuite/users/1051231434574456692166/password', []);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group google
     * @group slow
     */
    public function guest_user_cannot_change_google_user_password_validation()
    {
        $response =  $this->json('PUT','/api/v1/gsuite/users/1051231434574456692166/password', []);
        $response->assertStatus(401);
    }

}
