<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Incident;
use App\Models\User;
use Carbon\Carbon;
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

    /** @test */
    public function map()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);

        $mappedIncident = $incident->map();

        $this->assertEquals(1,$mappedIncident['id']);
        $this->assertEquals($user->id,$mappedIncident['user_id']);
        $this->assertEquals('Pepe Pardo Jeans',$mappedIncident['username']);
        $this->assertEquals('pepepardo@jeans.com',$mappedIncident['user_email']);
        $this->assertEquals('No funciona pc2 aula 15',$mappedIncident['subject']);
        $this->assertEquals('bla bla bla',$mappedIncident['description']);
        $this->assertNotNull($mappedIncident['created_at']);
        $this->assertNotNull($mappedIncident['updated_at']);
        $this->assertNotNull($mappedIncident['created_at_timestamp']);
        $this->assertNotNull($mappedIncident['updated_at_timestamp']);
        $this->assertNotNull($mappedIncident['formatted_created_at']);
        $this->assertNotNull($mappedIncident['formatted_updated_at']);

        $this->assertNull($mappedIncident['closed_at']);
        $this->assertNull($mappedIncident['formatted_closed_at']);
        $this->assertNull($mappedIncident['closed_at_timestamp']);

        $incident->close();
        $incident= $incident->fresh();
        $mappedIncident = $incident->map();
        $this->assertNotNull($mappedIncident['closed_at']);
        $this->assertNotNull($mappedIncident['formatted_closed_at']);
        $this->assertNotNull($mappedIncident['closed_at_timestamp']);
    }

    /**
     * @test
     */
    public function close()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);

        $this->assertNull($incident->closed_at);

        $incident->close();

        $incident = $incident->fresh();
        $this->assertNotNull($incident->closed_at);
    }

    /**
     * @test
     */
    public function open()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
        ])->assignUser($user);
        $incident->close();

        $incident->open();

        $incident = $incident->fresh();
        $this->assertNull($incident->closed_at);
    }

    /** @test */
    function can_get_formatted_created_at_date()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'created_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $incident->formatted_created_at);
    }

    /** @test */
    function can_get_formatted_closed_at_date()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'closed_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $incident->formatted_closed_at);
    }

    /** @test */
    function can_get_closed_at_timestamp()
    {
        $incident = Incident::create([
            'subject' => 'No funciona pc2 aula 15',
            'description' => 'bla bla bla',
            'closed_at' => Carbon::parse('2016-12-01 8:00pm')
        ]);

        $this->assertEquals('1480618800', $incident->closed_at_timestamp);
    }
}
