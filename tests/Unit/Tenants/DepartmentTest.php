<?php

namespace Tests\Unit\Tenants;

use App\Models\Department;
use App\Models\Study;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class DepartmentTest.
 *
 * @package Tests\Unit
 */
class DepartmentTest extends TestCase
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
    public function can_find_by_code()
    {
        $this->assertEmpty(Department::findByCode('SERVEIS'));
        $deparment = Department::firstOrCreate([
            'name' => 'Departament de serveis socioculturals i a la comunitat',
            'shortname' => 'Serveis socioculturals i a la comunitat',
            'code' => 'SERVEIS',
            'order' => 9,
            'head' => 1
        ]);
        $this->assertTrue($deparment->is(Department::findByCode('SERVEIS')));
    }

    /** @test */
    public function can_find_by_name()
    {
        $this->assertEmpty(Department::findByName('Departament de serveis socioculturals i a la comunitat'));
        $deparment = Department::firstOrCreate([
            'name' => 'Departament de serveis socioculturals i a la comunitat',
            'shortname' => 'Serveis socioculturals i a la comunitat',
            'code' => 'SERVEIS',
            'order' => 9,
            'head' => 1
        ]);
        $this->assertTrue($deparment->is(Department::findByName('Departament de serveis socioculturals i a la comunitat')));
    }

    /**
     * @test
     * @group curriculum
     */
    public function addStudy()
    {
        $informatica = Department::create([
            'name' => "Departament d'Informàtica",
            'shortname' => 'Informàtica',
            'code' => 'INF',
            'order' => 1
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
        $depInformatica = Department::create([
            'name' => 'Departament Informàtica',
            'shortname' => 'Informàtica',
            'code' => 'INFORMÀTICA',
            'order' => 1
        ]);

        $mappedDepartment = $depInformatica->map();

        $this->assertEquals(1,$mappedDepartment['id']);
        $this->assertEquals('Departament Informàtica',$mappedDepartment['name']);
        $this->assertEquals('Informàtica',$mappedDepartment['shortname']);
        $this->assertEquals('INFORMÀTICA',$mappedDepartment['code']);
        $this->assertSame(1,$mappedDepartment['order']);

        $this->assertNotNull($mappedDepartment['created_at']);
        $this->assertNotNull($mappedDepartment['updated_at']);
        $this->assertNotNull($mappedDepartment['created_at_timestamp']);
        $this->assertNotNull($mappedDepartment['updated_at_timestamp']);
        $this->assertNotNull($mappedDepartment['formatted_created_at']);
        $this->assertNotNull($mappedDepartment['formatted_updated_at']);
        $this->assertNotNull($mappedDepartment['formatted_created_at_diff']);
        $this->assertNotNull($mappedDepartment['formatted_updated_at_diff']);
        $this->assertEquals('departments',$mappedDepartment['api_uri']);
    }
}
