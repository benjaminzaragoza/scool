<?php

namespace Tests\Feature\Tenants;

use Illuminate\Contracts\Console\Kernel;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleSuiteAdminUsersControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleSuiteAdminUsersControllerTest extends BaseTenantTest
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
     * @group working
     * @group gsuite
     */
    public function show_google_admin_users()
    {

    }

    /**
     * @test
     * @group working
     */
    public function user_cannot_show_google_admin_users()
    {
//        Config::set('google.service.enable', true);
//        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
//        Config::set('google.admin_email', 'sergitur@iesebre.com');
//
//        $user = create(User::class);
//        $this->actingAs($user,'api');
//
//        $response = $this->json('GET','/api/v1/gsuite/users/');
//        $response->assertStatus(403);
    }



}
