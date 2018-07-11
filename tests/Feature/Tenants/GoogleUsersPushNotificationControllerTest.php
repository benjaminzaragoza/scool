<?php

namespace Tests\Feature\Tenants;

use App\Events\GoogleInvalidUserNotificationReceived;
use App\Events\GoogleUserNotificationReceived;
use App\Jobs\SyncGoogleUsers;
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
        Event::fake();
        GoogleWatch::create([
            'channel_id' => '123456789',
            'channel_type' => 'add',
            'token' => 'TOKEN'
        ]);
        $expiration = Carbon::now()->addHours(1);
        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-message-number' => 1,
            'x-goog-channel-expiration' => $expiration->toRfc7231String()
//            X-Goog-Channel-Expiration: Tue, 29 Oct 2013 20:32:02 GMT
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Ok',$result->result);

        $this->assertDatabaseHas('google_notifications',[
            'channel_id' => '123456789',
            'channel_type' => 'sync',
            'token' => 'TOKEN',
            'message_number' => 1,
            'expiration_time' => Carbon::parse($expiration)->toDateTimeString(),
            'valid' => true
        ]);

        Event::assertDispatched(GoogleUserNotificationReceived::class);
    }

    /**
     * @test
     * @group google
     */
    public function can_receive_google_suite_users_push_notifications_email()
    {
        Mail::fake();
        GoogleWatch::create([
            'channel_id' => '123456789',
            'channel_type' => 'add',
            'token' => 'TOKEN'
        ]);
        $expiration = Carbon::now()->addHours(1);
        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-message-number' => 1,
            'x-goog-channel-expiration' => $expiration->toRfc7231String()
//            X-Goog-Channel-Expiration: Tue, 29 Oct 2013 20:32:02 GMT
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
        Queue::fake();
        GoogleWatch::create([
            'channel_id' => '123456789',
            'channel_type' => 'add',
            'token' => 'TOKEN'
        ]);

        // Do not sinchronize users with syncs!!!
        $expiration = Carbon::now()->addHours(1);

        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-message-number' => 1,
            'x-goog-channel-expiration' => $expiration->toRfc7231String()
//            X-Goog-Channel-Expiration: Tue, 29 Oct 2013 20:32:02 GMT
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Ok',$result->result);
        Queue::assertNotPushed(SyncGoogleUsers::class);

        $expiration = Carbon::now()->addHours(1);
        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'add',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-message-number' => 1,
            'x-goog-channel-expiration' => $expiration->toRfc7231String()
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
        GoogleWatch::create([
            'channel_id' => '123456789',
            'channel_type' => 'add',
            'token' => 'TOKEN'
        ]);
        $expiration = Carbon::now()->subHours(1);
        $response = $this->post('/gsuite/notifications',[],[
            'X-Goog-resource-state' => 'sync',
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'x-goog-message-number' => 1,
            'x-goog-channel-expiration' => $expiration->toRfc7231String()
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Error',$result->result);

        $this->assertDatabaseHas('google_notifications',[
            'channel_id' => '123456789',
            'token' => 'TOKEN',
            'channel_type' => 'sync',
            'message_number' => 1,
            'expiration_time' => $expiration,
            'valid' => false
        ]);
    }

    /**
     * @test
     * @group google
     */
    public function can_receive_google_suite_invalid_users_push_notifications()
    {
        Event::fake();

        $response = $this->post('/gsuite/notifications',[],[
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'X-Goog-resource-state' => 'sync',
            'X-Goog-inventat' => 'PROVA',
            'x-goog-message-number' => 1,
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
            'x-goog-channel-id' => '123456789',
            'x-goog-channel-token' => 'TOKEN',
            'X-Goog-resource-state' => 'sync',
            'X-Goog-inventat' => 'PROVA',
            'x-goog-message-number' => 1,
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('Error',$result->result);

        Mail::assertSent(GoogleInvalidUserNotificationReceivedEmail::class);
    }
}
