<?php

namespace Tests\Unit\Tenants\Changelog;

use App\Console\Kernel;
use App\Events\Incidents\IncidentReplyAdded;
use App\Listeners\Incidents\NotifyIncidentReplyAdded;
use App\Models\DatabaseNotification;
use App\Notifications\Incidents\IncidentReplyAdded as IncidentReplyAddedNotification;
use App\Models\Incident;
use App\Models\Reply;
use App\Models\User;
use App\Notifications\SimpleNotification;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class DatabaseNotificationTest.
 *
 * @package Tests\Unit\Tenants
 */
class DatabaseNotificationTest extends TestCase
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
     */
    public function mapSimple()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $user->notify(new SimpleNotification('Notificació exemple'));
        $notification = DatabaseNotification::first();

        $mappedNotification = $notification->mapSimple();

        $this->assertTrue(is_valid_uuid($mappedNotification['id']));
        $this->assertEquals('App\Notifications\SimpleNotification',$mappedNotification['type']);
        $this->assertEquals(User::class, $mappedNotification['notifiable_type']);
        $this->assertEquals(1, $mappedNotification['notifiable_id']);
        $this->assertEquals('Notificació exemple', $mappedNotification['data']['title']);
        $this->assertEquals('notifications', $mappedNotification['api_uri']);
        $this->assertNull($mappedNotification['read_at']);
        $this->assertNotNull($mappedNotification['created_at']);
        $this->assertNotNull($mappedNotification['updated_at']);
        $this->assertNotNull($mappedNotification['created_at_timestamp']);
        $this->assertNotNull($mappedNotification['updated_at_timestamp']);
        $this->assertNotNull($mappedNotification['formatted_created_at']);
        $this->assertNotNull($mappedNotification['formatted_updated_at']);
        $this->assertNotNull($mappedNotification['formatted_created_at_diff']);
        $this->assertNotNull($mappedNotification['formatted_updated_at_diff']);
    }

    /**
     * @test
     */
    public function map()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $user->notify(new SimpleNotification('Notificació exemple'));
        $notification = DatabaseNotification::first();

        $mappedNotification = $notification->map();

        $this->assertTrue(is_valid_uuid($mappedNotification['id']));
        $this->assertEquals('App\Notifications\SimpleNotification',$mappedNotification['type']);
        $this->assertTrue($user->is($mappedNotification['notifiable']));
        $this->assertEquals(User::class, $mappedNotification['notifiable_type']);
        $this->assertEquals(1, $mappedNotification['notifiable_id']);
        $this->assertEquals('Notificació exemple', $mappedNotification['data']['title']);
        $this->assertEquals('notifications', $mappedNotification['api_uri']);
        $this->assertNull($mappedNotification['read_at']);
        $this->assertNotNull($mappedNotification['created_at']);
        $this->assertNotNull($mappedNotification['updated_at']);
        $this->assertNotNull($mappedNotification['created_at_timestamp']);
        $this->assertNotNull($mappedNotification['updated_at_timestamp']);
        $this->assertNotNull($mappedNotification['formatted_created_at']);
        $this->assertNotNull($mappedNotification['formatted_updated_at']);
        $this->assertNotNull($mappedNotification['formatted_created_at_diff']);
        $this->assertNotNull($mappedNotification['formatted_updated_at_diff']);
    }

}
