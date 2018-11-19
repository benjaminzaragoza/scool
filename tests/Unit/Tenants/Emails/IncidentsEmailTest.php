<?php

namespace Tests\Unit\Tenants\Emails;

use App\Listeners\Incidents\SendIncidentCreatedEmail;
use App\Mail\Incidents\IncidentCreated;
use App\Models\Incident;
use App\Models\User;
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


}
