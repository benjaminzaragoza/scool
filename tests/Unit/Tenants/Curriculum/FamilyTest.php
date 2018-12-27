<?php

namespace Tests\Unit\Tenants\Curriculum;

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
    public function find_specialty_by_code()
    {
        $this->assertNull(Family::findByCode('INF'));
        $family = Family::firstOrCreate([
            'code' => 'INF',
            'name' => 'Informàtica'
        ]);

        $this->assertTrue($family->is(Family::findByCode('INF')));
    }

    /**
     * @test
     * @group curriculum
     */
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

    /**
     * @test
     * @group curriculum
     * @group teachers
     */
    public function map()
    {
        $informatica = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);
        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $informatica->addStudy($dam);

        $mappedFamily = $informatica->map();

        $this->assertEquals(1,$mappedFamily['id']);
        $this->assertEquals('Informàtica',$mappedFamily['name']);
        $this->assertEquals('INF',$mappedFamily['code']);

        $this->assertNotNull($mappedFamily['created_at']);
        $this->assertNotNull($mappedFamily['updated_at']);
        $this->assertNotNull($mappedFamily['created_at_timestamp']);
        $this->assertNotNull($mappedFamily['updated_at_timestamp']);
        $this->assertNotNull($mappedFamily['formatted_created_at']);
        $this->assertNotNull($mappedFamily['formatted_updated_at']);
        $this->assertNotNull($mappedFamily['formatted_created_at_diff']);
        $this->assertNotNull($mappedFamily['formatted_updated_at_diff']);
        $this->assertEquals('families',$mappedFamily['api_uri']);
        $this->assertCount(1,$mappedFamily['studies']);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$mappedFamily['studies'][0]['name']);
    }
}
