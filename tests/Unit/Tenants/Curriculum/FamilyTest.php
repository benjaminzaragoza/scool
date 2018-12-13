<?php

namespace Tests\Unit\Tenants;

use App\Models\Family;
use App\Models\Study;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class FamilyTest.
 *
 * @package Tests\Unit
 */
class FamilyTest extends TestCase
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
    public function addStudy()
    {
        $informatica = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);
        $this->assertEmpty($informatica->studies);

        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $informatica->addStudy($dam);
        $informatica = $informatica->fresh();
        $this->assertCount(1,$informatica->studies);
        $this->assertTrue($informatica->studies[0]->is($dam));

    }
}
