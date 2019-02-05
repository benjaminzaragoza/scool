<?php

namespace Tests\Unit\Tenants\Notifications;

use App\Console\Kernel;
use App\Events\Incidents\IncidentReplyAdded;
use App\Listeners\Incidents\NotifyIncidentReplyAdded;
use App\Notifications\Incidents\IncidentReplyAdded as IncidentReplyAddedNotification;
use App\Models\Incident;
use App\Models\Log;
use App\Models\Reply;
use App\Models\User;
use Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class IncidentsNotificationsTest.
 *
 * @package Tests\Unit\Tenants
 */
class IncidentsNotificationsTest extends TestCase
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

    /** @test */
    public function replyAdded()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));
        $reply = Reply::create([
            'body' => 'Ja hem resolt la incidència',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply);

        $event = new IncidentReplyAdded($incident,$reply);
        $listener = new NotifyIncidentReplyAdded();

        Notification::fake();
        $listener->handle($event);
        Notification::assertSentTo(
            $user,
            IncidentReplyAddedNotification::class,
            function ($notification, $channels) use ($incident) {
                return $notification->incident->id === $incident->id &&
                    in_array('database',$channels);
            }
        );
    }

}
