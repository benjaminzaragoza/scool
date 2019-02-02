<?php

namespace Tests\Feature\Tenants\Api\People;

use App\Notifications\SampleNotification;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class NotificationsControllerTest.
 *
 * @package Tests\Feature
 */
class NotificationsControllerTest extends BaseTenantTest
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
     * @test
     * @group notifications
     */
    public function notifications_manager_can_list_all__notifications()
    {
        $this->withoutExceptionHandling();
        $user = $this->loginAsNotificationsManager('api');
        set_sample_notifications_to_user($user);
        $user->notifications[1]->markAsRead();
        $response = $this->json('GET','/api/v1/notifications');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(3,$result);
        $this->assertEquals('Notification 1',$result[0]->data->title);
        $this->assertEquals(SampleNotification::class,$result[0]->type);
        $this->assertEquals('Notification 2',$result[1]->data->title);
        $this->assertEquals(SampleNotification::class,$result[1]->type);
        $this->assertEquals('Notification 3',$result[2]->data->title);
        $this->assertEquals(SampleNotification::class,$result[2]->type);
    }
}
