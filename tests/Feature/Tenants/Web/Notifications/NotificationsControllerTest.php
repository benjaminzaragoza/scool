<?php

namespace Tests\Feature\Web\Notifications;

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
    public function show_notifications_module()
    {
        $user = $this->loginAsSuperAdmin();
        set_sample_notifications_to_user($user);

        $response = $this->get('/notifications');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.notifications.index');
        $response->assertViewHas('notifications', function ($returnedNotifications) {
            return
                count($returnedNotifications) === 3 &&
                $returnedNotifications[0]->data['title'] === 'Notification 1' &&
                $returnedNotifications[1]->data['title'] === 'Notification 2' &&
                $returnedNotifications[2]->data['title'] === 'Notification 3';
        });
    }

    /**
     * @test
     * @group notifications
     */
    public function regular_user_cannot_show_notifications_module()
    {
        $this->login();
        $response = $this->get('/notifications');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group notifications
     */
    public function guest_user_cannot_show_notifications_module()
    {
        $response = $this->get('/notifications');
        $response->assertRedirect('login');
    }

}
