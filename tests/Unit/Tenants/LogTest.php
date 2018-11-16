<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class LogTest.
 *
 * @package Tests\Unit\Tenants
 */
class LogTest extends TestCase
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
    public function map()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $log = Log::create([
            'text' => 'Ha creat la incidència TODO_LINK_INCIDENCIA',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => $user->id,
            'icon' => 'home',
            'color' => 'teal'
        ]);

        $mappedLog = $log->map();

        $this->assertEquals(1,$mappedLog['id']);
        $this->assertEquals('Ha creat la incidència TODO_LINK_INCIDENCIA',$mappedLog['text']);
        $this->assertNotNull($mappedLog['time']);
        $this->assertNotNull($mappedLog['human_time']);
        $this->assertNotNull($mappedLog['timestamp']);
        $this->assertEquals('update',$mappedLog['action_type']);
        $this->assertEquals('update',$mappedLog['action']->name);
        $this->assertEquals('Edició',$mappedLog['action']->text);
        $this->assertEquals('edit',$mappedLog['action']->icon);
        $this->assertEquals('Incidents',$mappedLog['module_type']);
        $this->assertEquals('Incidents',$mappedLog['module']->name);
        $this->assertEquals('Incidències',$mappedLog['module']->text);
        $this->assertEquals('build',$mappedLog['module']->icon);
        $this->assertTrue($mappedLog['user']->is($user));
        $this->assertEquals('home',$mappedLog['icon']);
        $this->assertEquals('teal',$mappedLog['color']);
    }
}
