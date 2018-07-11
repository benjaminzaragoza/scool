<?php

namespace Tests\Feature\Tenants;

use App\Events\GoogleInvalidUserNotificationReceived;
use App\Events\GoogleUserNotificationReceived;
use App\Jobs\SyncGoogleUsers;
use App\Jobs\WatchGoogleUsers;
use App\Mail\GoogleInvalidUserNotificationReceived as GoogleInvalidUserNotificationReceivedEmail;
use App\Mail\GoogleUserNotificationReceived as GoogleUserNotificationReceivedEmail;
use App\Models\GoogleWatch;
use Carbon\Carbon;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Mail;
use Queue;
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
    public function can_receive_google_suite_users_push_notifications()
    {
        //        $this->withoutExceptionHandling();
        Event::fake();
        GoogleWatch::create([
            'channel_id' => '123456789',
            'channel_type' => 'add',
            'token' => 'TOKEN'
        ]);
        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-channel-expiration' => Carbon::now()->addHours(1)->timestamp*1000
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Ok',$result->result);

        Event::assertDispatched(GoogleUserNotificationReceived::class);
    }

    /**
     * @test
     * @group google
     */
    public function can_receive_google_suite_users_push_notifications_email()
    {
        //        $this->withoutExceptionHandling();
        Mail::fake();
        GoogleWatch::create([
            'channel_id' => '123456789',
            'channel_type' => 'add',
            'token' => 'TOKEN'
        ]);
        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-channel-expiration' => Carbon::now()->addHours(1)->timestamp*1000
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Ok',$result->result);
        Mail::assertSent(GoogleUserNotificationReceivedEmail::class);
    }

    /**
     * @test
     * @group working
     * @group google
     */
    public function can_receive_google_suite_users_push_notifications_job()
    {
        //        $this->withoutExceptionHandling();
        Queue::fake();
        GoogleWatch::create([
            'channel_id' => '123456789',
            'channel_type' => 'add',
            'token' => 'TOKEN'
        ]);

        // Do not sinchronize users with syncs!!!
        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-channel-expiration' => Carbon::now()->addHours(1)->timestamp*1000
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Ok',$result->result);
        Queue::assertNotPushed(SyncGoogleUsers::class);

        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'add',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-channel-expiration' => Carbon::now()->addHours(1)->timestamp*1000
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Ok',$result->result);
        Queue::assertPushed(SyncGoogleUsers::class);
    }

    /**
     * @test
     * @group working
     * @group google
     */
    public function can_receive_google_suite_users_push_notifications_invalid_expiration_date()
    {
                $this->withoutExceptionHandling();
        GoogleWatch::create([
            'channel_id' => '123456789',
            'channel_type' => 'add',
            'token' => 'TOKEN'
        ]);
        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-channel-expiration' => Carbon::now()->subHours(1)->timestamp*1000
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Error',$result->result);
    }

    /**
     * @test
     * @group google
     */
    public function can_receive_google_suite_invalid_users_push_notifications()
    {
        Event::fake();

        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'X-Goog-inventat' => 'PROVA'
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Error',$result->result);

        Event::assertDispatched(GoogleInvalidUserNotificationReceived::class);
    }

    /**
     * @test
     * @group google
     */
    public function can_receive_google_suite_invalid_users_push_notifications_email()
    {
        Mail::fake();

        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'X-Goog-inventat' => 'PROVA'
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Error',$result->result);

        Mail::assertSent(GoogleInvalidUserNotificationReceivedEmail::class);
    }
}
