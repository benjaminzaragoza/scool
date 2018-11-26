<?php

namespace Tests\Unit\Tenants\Emails;

use App\Listeners\Incidents\SendIncidentAssignedEmail;
use App\Listeners\Incidents\SendIncidentClosedEmail;
use App\Listeners\Incidents\SendIncidentCreatedEmail;
use App\Listeners\Incidents\SendIncidentDeletedEmail;
use App\Listeners\Incidents\SendIncidentDesassignedEmail;
use App\Listeners\Incidents\SendIncidentDescriptionUpdateEmail;
use App\Listeners\Incidents\SendIncidentOpenedEmail;
use App\Listeners\Incidents\SendIncidentReplyAddedEmail;
use App\Listeners\Incidents\SendIncidentSubjectUpdateEmail;
use App\Listeners\Incidents\SendIncidentTagAddedEmail;
use App\Listeners\Incidents\SendIncidentTagRemovedEmail;
use App\Mail\Incidents\IncidentAssigned;
use App\Mail\Incidents\IncidentClosed;
use App\Mail\Incidents\IncidentCommentAdded;
use App\Mail\Incidents\IncidentCreated;
use App\Mail\Incidents\IncidentDeleted;
use App\Mail\Incidents\IncidentDesassigned;
use App\Mail\Incidents\IncidentDescriptionModified;
use App\Mail\Incidents\IncidentOpened;
use App\Mail\Incidents\IncidentSubjectModified;
use App\Mail\Incidents\IncidentTagged;
use App\Mail\Incidents\IncidentUntagged;
use App\Models\Incident;
use App\Models\Reply;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Tests\BaseTenantTest;

/**
 * Class SendIncidentCreatedEmail.
 *
 * @package Tests\Unit
 */
class IncidentsEmailTest extends BaseTenantTest
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
    public function sendIncidentCreatedEmail()
    {
        $listener = new SendIncidentCreatedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        $event = (Object) [
            'incident' => $incident
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentCreated::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentClosedEmail()
    {
        $listener = new SendIncidentClosedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        Auth::login($user);
        $incident->close();
        $event = (Object) [
            'incident' => $incident
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentClosed::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentOpenedEmail()
    {
        $listener = new SendIncidentOpenedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        Auth::login($user);
        $incident->close();
        $incident->open();
        $event = (Object) [
            'incident' => $incident
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentOpened::class, function ($mail) use ($incident, $user) {
            return $mail->incident->is($incident) && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentDeletedEmail()
    {
        $listener = new SendIncidentDeletedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        Auth::login($user);
        $oldIncident = clone($incident);
        $incident->delete();
        $event = (Object) [
            'oldIncident' => $oldIncident
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentDeleted::class, function ($mail) use ($incident, $user) {
            return $mail->incident->is($incident) && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentDescriptionUpdatedEmail()
    {
        $listener = new SendIncidentDescriptionUpdateEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignuser($user);
        $event = (Object) [
            'incident' => $incident
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentDescriptionModified::class, function ($mail) use ($incident, $user) {
            return $mail->incident->is($incident) && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentSubjectUpdatedEmail()
    {
        $listener = new SendIncidentSubjectUpdateEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        $event = (Object) [
            'incident' => $incident
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentSubjectModified::class, function ($mail) use ($incident, $user) {
            return $mail->incident->is($incident) && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentReplyAddedEmail()
    {
        $listener = new sendIncidentReplyAddedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        $reply = Reply::create([
            'body' => 'Ja està tot arreglat',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply);
        $event = (Object) [
            'incident' => $incident,
            'reply' => $reply
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentCommentAdded::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentTagAddedEmail()
    {
        $listener = new sendIncidentTagAddedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        $reply = Reply::create([
            'body' => 'Ja està tot arreglat',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply);
        $event = (Object) [
            'incident' => $incident,
            'reply' => $reply
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentTagged::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentTagRemovedEmail()
    {
        $listener = new sendIncidentTagRemovedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        $reply = Reply::create([
            'body' => 'Ja està tot arreglat',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply);
        $event = (Object) [
            'incident' => $incident,
            'reply' => $reply
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentUntagged::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentAssignedEmail()
    {
        $listener = new sendIncidentAssignedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);

        $event = new IncidentAssigned($incident);
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentAssigned::class, function ($mail) use ($incident, $user) {
            return $mail->incident->is($incident) && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

    /**
     * @test
     */
    public function sendIncidentDesassignedEmail()
    {
        $listener = new sendIncidentDesassignedEmail();
        $incident = Incident::create([
            'subject' => 'No funciona res Aula 20',
            'description' => 'Bla bla bla'
        ]);
        $user= factory(User::class)->create();
        $incident->assignUser($user);
        $reply = Reply::create([
            'body' => 'Ja està tot arreglat',
            'user_id' => $user->id
        ]);
        $incident->addReply($reply);
        $event = new IncidentDesassigned($incident);
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentDesassigned::class, function ($mail) use ($incident, $user) {
            return $mail->incident->id === $incident->id && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }

}
