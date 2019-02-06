<?php

namespace Tests\Feature\Tenants\Api\Notifications;

use App\Models\User;
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
    public function notifications_manager_can_list_all_notifications()
    {
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

    /**
     * @test
     * @group notifications
     */
    public function regular_user_cannot_list_all_notifications()
    {
        $user = $this->login('api');
        set_sample_notifications_to_user($user);
        $user->notifications[1]->markAsRead();
        $response = $this->json('GET','/api/v1/notifications');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group notifications
     */
    public function guest_user_cannot_list_all_notifications()
    {
        $user = factory(User::class)->create();
        set_sample_notifications_to_user($user);
        $user->notifications[1]->markAsRead();
        $response = $this->json('GET','/api/v1/notifications');
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group notifications
     */
    public function notifications_manager_can_remove_multiple_notifications()
    {
        $user = $this->loginAsNotificationsManager('api');
        set_sample_notifications_to_user($user);
        $this->assertCount(3,$user->notifications);
        $response = $this->json('POST','/api/v1/notifications/multiple', [
            'notifications' => $user->notifications->pluck('id')->toArray()
        ]);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertCount(0,$user->notifications);
    }

    /**
     * @test
     * @group notifications
     */
    public function regular_user_cannot_remove_multiple_notifications()
    {
        $user = $this->login('api');
        set_sample_notifications_to_user($user);
        $this->assertCount(3,$user->notifications);
        $response = $this->json('POST','/api/v1/notifications/multiple', [
            'notifications' => $user->notifications->pluck('id')->toArray()
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group notifications
     */
    public function guest_user_cannot_remove_multiple_notifications()
    {
        $user = factory(User::class)->create();
        set_sample_notifications_to_user($user);
        $this->assertCount(3,$user->notifications);
        $response = $this->json('POST','/api/v1/notifications/multiple', [
            'notifications' => $user->notifications->pluck('id')->toArray()
        ]);
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group notifications
     */
    public function notifications_manager_can_remove_a_notification()
    {
        $this->withoutExceptionHandling();
        $user = $this->loginAsNotificationsManager('api');
        set_sample_notifications_to_user($user);
        $this->assertCount(3,$user->notifications);
        $response = $this->json('DELETE','/api/v1/notifications/' . $user->notifications->first()->id);
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertCount(2,$user->notifications);
    }
}
