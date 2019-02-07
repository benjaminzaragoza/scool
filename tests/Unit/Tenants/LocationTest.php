<?php

namespace Tests\Unit\Tenants;

use App\Models\Location;
use App\Models\User;
use Artisan;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class LocationTest.
 *
 * @package Tests\Unit
 */
class LocationTest extends TestCase
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
    public function find_by_name()
    {
        $this->assertNull(Location::findByName('Tortosa'));

        $tortosa = Location::create([
            'name' => 'TORTOSA',
            'postalcode' => '43500'
        ]);

        $this->assertTrue($tortosa->is(Location::findByName('TORTOSA')));
    }

    /**
     * @test
     * @slow
     */
    public function province()
    {
        seed_provinces();
        Artisan::call('db:seed', [
            '--class' => 'LocationsTableSeeder'
        ]);
        $location = Location::findByName('TORTOSA');
        $this->assertEquals('Tarragona',$location->province);
    }

}
