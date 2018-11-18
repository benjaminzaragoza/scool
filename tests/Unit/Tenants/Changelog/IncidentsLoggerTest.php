<?php

namespace Tests\Unit\Tenants\Changelog;

use App\Console\Kernel;
use App\Listeners\Incidents\IncidentLogger;
use App\Models\Incident;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class IncidentsLoggerTest.
 *
 * @package Tests\Unit\Tenants
 */
class IncidentsLoggerTest extends TestCase
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
    public function stored()
    {
        $incident= Incident::create([
            'subject' => 'No funciona res aula 20',
            'description' => 'Bla bla bla',
        ]);
        $incident->assignUser($user = factory(User::class)->create([]));

        $event = (Object) [
            'incident' => $incident
        ];
        IncidentLogger::stored($event);
        $log = Log::first();
        $this->assertEquals($log->text,'Ha creat la incidència No funciona res aula 20');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, $user->id);
        $this->assertEquals($log->action_type,'store');
        $this->assertEquals($log->module_type,'Incidents');
        $this->assertEquals($log->loggable_id,$incident->id);
        $this->assertEquals($log->loggable_type,Incident::class);
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'add');
        $this->assertEquals($log->color,'success');
    }
}
