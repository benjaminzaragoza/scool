<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Listeners\Incidents\IncidentLogger;
use App\Models\Log;
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
    public function todo()
    {
        $event = (Object) [
            'credentials' => [
                'email' => 'prova@gmail.com'
            ]
        ];
        IncidentLogger::incorrectAttempt($event);
        $log = Log::first();
        $this->assertEquals($log->text,"Intent de login incorrecte amb l'usuari <strong>prova@gmail.com</strong>");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, null);
        $this->assertEquals($log->action_type,'error');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,null);
        $this->assertEquals($log->loggable_type,null);
        $this->assertNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'error');
        $this->assertEquals($log->color,'error');
    }
}
