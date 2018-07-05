<?php

namespace Tests\Feature\Tenants;

use Illuminate\Contracts\Console\Kernel;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleSuiteUsersPushNotificationControllerTest.
 *
 * @package Tests\Feature
 */
class GoogleSuiteUsersPushNotificationControllerTest extends BaseTenantTest
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
     */
    public function can_receive_google_suite_users_push_notifications()
    {
        $response = $this->post('/gsuite/notifications',[
            'kind' => "admin#directory#user",
            'id' => 2341412,
            'etag' => 'weqqw4321',
            'primaryEmail' => 'prova@iesebre.com'
        ]);

        $response->assertSuccessful();
        $response->dump();
    }
}
