<?php

namespace Tests\Unit\Tenants\Emails;

use App\Listeners\Incidents\SendIncidentClosedEmail;
use App\Listeners\Incidents\SendIncidentCreatedEmail;
use App\Listeners\Incidents\SendIncidentDeletedEmail;
use App\Listeners\Incidents\SendIncidentOpenedEmail;
use App\Mail\Incidents\IncidentClosed;
use App\Mail\Incidents\IncidentCreated;
use App\Mail\Incidents\IncidentDeleted;
use App\Mail\Incidents\IncidentOpened;
use App\Models\Incident;
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
        $incident->delete();
        $event = (Object) [
            'incident' => $incident
        ];
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        Mail::fake();
        $listener->handle($event);
        Mail::assertQueued(IncidentDeleted::class, function ($mail) use ($incident, $user) {
            return $mail->incident->is($incident) && $mail->hasTo($user->email) && $mail->hasCc('incidencies@iesebre.com');
        });
    }


}
