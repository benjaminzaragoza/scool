<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Incident;
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

        $incident = Incident::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $oldIncident = clone($incident);
        $incident->subject = 'No funciona PC1 Aula 6';
        $incident->save();
        $log = Log::create([
            'text' => "Ha creat la incidència $incident->subject",
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => $user->id,
            'loggable_id' => $incident->id,
            'loggable_type' => Incident::class,
            'new_loggable' => $incident->toJson(),
            'old_loggable' => $oldIncident->toJson(),
            'new_value' => 'valor nou',
            'old_value' => 'valor àntic',
            'icon' => 'home',
            'color' => 'teal'
        ]);

        $mappedLog = $log->map();

        $this->assertEquals($log->id,$mappedLog['id']);
        $this->assertEquals('Ha creat la incidència No funciona PC1 Aula 6',$mappedLog['text']);
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
        $this->assertEquals($user->id,$mappedLog['user_id']);
        $this->assertEquals($incident->id,$mappedLog['loggable_id']);
        $this->assertTrue($mappedLog['loggable']->is($incident));
        $this->assertEquals($oldIncident->toJson(),$mappedLog['old_loggable']);
        $this->assertEquals($incident->toJson(),$mappedLog['new_loggable']);
        $this->assertEquals($log->old_value,$mappedLog['old_value']);
        $this->assertEquals($log->new_value,$mappedLog['new_value']);
        $this->assertEquals($user->id,$mappedLog['user_id']);
        $this->assertTrue($mappedLog['user']->is($user));
        $this->assertEquals('home',$mappedLog['icon']);
        $this->assertEquals('teal',$mappedLog['color']);
    }
}
