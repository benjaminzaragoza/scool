<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Incident;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class IncidentTest.
 *
 * @package Tests\Unit\Tenants
 */
class IncidentTest extends TestCase
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
    public function assign_user() {
        $incident = Incident::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($incident->user);
        $result = $incident->assignUser($user = factory(User::class)->create());
        $incident = $incident->fresh();
        $this->assertNotNull($incident->user);
        $this->assertEquals($user->id,$incident->user->id);
        $this->assertTrue($incident->is($result));
    }

    /** @test */
    public function assign_user_by_id() {
        $incident = Incident::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($incident->user);
        $user = factory(User::class)->create();
        $result = $incident->assignUser($user->id);
        $incident = $incident->fresh();

        $this->assertNotNull($incident->user);
        $this->assertEquals($user->id,$incident->user->id);
        $this->assertTrue($incident->is($result));
    }

    /** @test */
    public function throwns_exception_assigning_incorrect_user() {
        $incident = Incident::create([
            'subject' => 'No funciona PC aula 5',
            'description' => 'bla bla bla'
        ]);
        $this->assertNull($incident->user);
        try {
            $incident->assignUser('pepe');
        } catch (\InvalidArgumentException $e) {

        }
    }
}
