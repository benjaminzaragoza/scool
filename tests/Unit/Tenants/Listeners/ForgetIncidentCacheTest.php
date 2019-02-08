<?php

namespace Tests\Unit\Tenants\Listeners;

use App\Events\Incidents\IncidentStored;
use App\Listeners\ForgetIncidentsCache;
use App\Models\Incident;
use App\Models\User;
use Cache;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ForgetIncidentCacheTest.
 *
 * @package Tests\Unit
 */
class ForgetIncidentCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

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
    public function can_forget_incidents_key()
    {
        $listener = new ForgetIncidentsCache();
        $incident = Incident::create([
            'subject' => 'prova',
            'description' => 'Bla bla bla'
        ]);

        Cache::shouldReceive('forget')
            ->once()
            ->with(Incident::INCIDENTS_CACHE_KEY);

        $listener->handle(new IncidentStored($incident));
    }
}
