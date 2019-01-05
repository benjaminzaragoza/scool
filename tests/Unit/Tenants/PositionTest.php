<?php

namespace Tests\Unit\Tenants;

use App\Models\Position;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class PositionsTest.
 *
 * @package Tests\Unit
 */
class PositionsTest extends TestCase
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
        $this->assertNull(Position::findByName('040'));

        $position = Position::create([
            'name' => 'Director',
        ]);

        $this->assertTrue($position->is(Position::findByName('Director')));
    }

    /**
     * @test
     * @group curriculum
     */
    public function map()
    {
        $position = Position::create([
            'name' => 'Director',
            'shortname' => 'Director',
            'code' => 'DIRE'
        ]);

        $mappedPosition = $position->map();

        $this->assertSame(1,$mappedPosition['id']);
        $this->assertSame('Director',$mappedPosition['name']);
        $this->assertSame('Director',$mappedPosition['shortname']);
        $this->assertSame('DIRE',$mappedPosition['code']);
        $this->assertSame('positions',$mappedPosition['api_uri']);
        $this->assertNotNull($mappedPosition['created_at']);
        $this->assertNotNull($mappedPosition['updated_at']);
        $this->assertNotNull($mappedPosition['created_at_timestamp']);
        $this->assertNotNull($mappedPosition['updated_at_timestamp']);
        $this->assertNotNull($mappedPosition['formatted_created_at']);
        $this->assertNotNull($mappedPosition['formatted_updated_at']);
        $this->assertNotNull($mappedPosition['formatted_created_at_diff']);
        $this->assertNotNull($mappedPosition['formatted_updated_at_diff']);
    }
}
