<?php

namespace Tests\Feature\Web\Notifications;

use App\Models\User;
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
        $this->withoutExceptionHandling();
        factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $user = $this->loginAsSuperAdmin();
        set_sample_notifications_to_user($user);
        sample_notifications();

        $response = $this->get('/notifications');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.notifications.index');
        $response->assertViewHas('userNotifications', function ($returnedUserNotifications) {
            return
                count($returnedUserNotifications) === 3 &&
                $returnedUserNotifications[0]->data['title'] === 'Notification 1' &&
                $returnedUserNotifications[1]->data['title'] === 'Notification 2' &&
                $returnedUserNotifications[2]->data['title'] === 'Notification 3';
        });
        $response->assertViewHas('notifications', function ($returnedNotifications) {
            return
                count($returnedNotifications) === 5;
        });
        $response->assertViewHas('users', function ($returnedUsers) use ($user) {
            return
                count($returnedUsers) === 4 &&
                $returnedUsers[0]['name'] === 'Pepe Pardo Jeans' &&
                $returnedUsers[0]['email'] === 'pepepardo@jeans.com' &&
                $returnedUsers[1]['name'] === $user->name &&
                $returnedUsers[1]['email'] === $user->email;
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
