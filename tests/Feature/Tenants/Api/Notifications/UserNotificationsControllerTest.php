<?php

namespace Tests\Feature\Tenants\Api\People;

use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserNotificationsControllerTest.
 *
 * @package Tests\Feature
 */
class UserNotificationsControllerTest extends BaseTenantTest
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

    /** @test */
    public function user_can_get_his_owned_notifications()
    {
        $user = $this->login('api');
        set_sample_notifications_to_user($user);
        $response = $this->json('GET','/api/v1/user/notifications/');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        dd($result);
    }

    /**
     * @test
     * @group notifications
     */
    public function user_can_get_his_owned_notifications_zero_notifications()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/user/notifications/');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(0,$result);
    }

}
