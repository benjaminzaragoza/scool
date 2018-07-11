<?php

namespace Tests\Feature\Tenants;

use App\Events\InvalidGoogleUserNotificationReceived;
use Event;
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
     * @group google
     */
    public function can_receive_google_invalid_suite_users_push_notifications()
    {
        $this->withoutExceptionHandling();
        Event::fake();

        $response = $this->post('/gsuite/notifications',[
            'kind' => "admin#directory#user",
            'id' => 2341412,
            'etag' => 'weqqw4321',
            'primaryEmail' => 'prova@iesebre.com'
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Error', $result->result);

        Event::assertDispatched(InvalidGoogleUserNotificationReceived::class);
    }
}
