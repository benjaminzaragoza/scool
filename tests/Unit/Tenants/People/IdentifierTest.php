<?php

namespace Tests\Unit\Tenants\People;

use App\Models\GoogleUser;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Location;
use App\Models\Person;
use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class IdentifierTest.
 *
 * @package Tests\Unit
 */
class IdentifierTest extends TestCase
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

    /**
     * @test
     */
    public function map()
    {
        $identifierType = IdentifierType::create(['name' => 'NIF']);
        $mappedIdentifierType = $identifierType->map();
        $this->assertEquals(1,$mappedIdentifierType['id']);
        $this->assertEquals('NIF',$mappedIdentifierType['name']);
    }
}
