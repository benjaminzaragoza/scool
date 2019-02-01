<?php

namespace Tests\Feature\Tenants\Api\People;

use App\Notifications\SampleNotification;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserUnreadNotificationsControllerTest.
 *
 * @package Tests\Feature
 */
class UserUnreadNotificationsControllerTest extends BaseTenantTest
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
    public function user_can_get_his_owned_unread_notifications()
    {
        $user = $this->login('api');
        set_sample_notifications_to_user($user);
        $user->notifications[1]->markAsRead();
        $response = $this->json('GET','/api/v1/user/unread_notifications/');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(2,$result);
        $this->assertEquals('Notification 1',$result[0]->data->title);
        $this->assertEquals(SampleNotification::class,$result[0]->type);
        $this->assertEquals('Notification 3',$result[1]->data->title);
        $this->assertEquals(SampleNotification::class,$result[1]->type);
    }

    /**
     * @test
     * @group notifications
     */
    public function guest_user_can_get_his_owned_unread_notifications()
    {
        $response = $this->json('GET','/api/v1/user/unread_notifications/');
        $response->assertStatus(401);
    }

}
