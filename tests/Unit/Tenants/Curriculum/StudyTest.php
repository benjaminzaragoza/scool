<?php

namespace Tests\Unit\Tenants;

use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\StudyTag;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class StudyTest.
 *
 * @package Tests\Unit
 */
class StudyTest extends TestCase
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
     * @group curriculum
     */
    public function assignFamily()
    {
        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $this->assertNull($dam->family);
        $informatica = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF'
        ]);
        $dam->assignFamily($informatica);
        $dam = $dam->fresh();
        $this->assertNotNull($dam->family);
        $this->assertTrue($dam->family->is($informatica));
    }

    /**
     * @test
     * @group curriculum
     */
    public function assignDepartment()
    {
        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $this->assertNull($dam->department);
        $informatica = Department::create([
            'name' => "Departament d'Informàtica",
            'shortname' => 'Informàtica',
            'code' => 'INF',
            'order' => 1
        ]);
        $dam->assignDepartment($informatica);
        $dam = $dam->fresh();
        $this->assertNotNull($dam->department);
        $this->assertTrue($dam->department->is($informatica));
    }

    /**
     * @test
     * @group curriculum
     */
    public function assignTag()
    {
        $dam = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Apps Multiplataforma',
            'code' => 'DAM'
        ]);
        $this->assertCount(0, $dam->tags);
        $loe = StudyTag::create([
            'value' => 'LOE',
            'description' => 'Ley Orgànica de Educación'
        ]);
        $dam->assignTag($loe);
        $dam = $dam->fresh();
        $this->assertCount(1,$dam->tags);
        $this->assertTrue($dam->tags[0]->is($loe));
    }

    /**
     * @test
     * @group curriculum
     */
    public function map()
    {
        $department = Department::create([
            'name' => "Departament d'Informàtica",
            'shortname' => 'Informàtica',
            'code' => 'INF',
            'order' => 1
        ]);

        $family = Family::create([
            'name' => 'Informàtica',
            'code' => 'INF',
        ]);

        $study = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
            'department_id' => $department->id,
            'family_id' => $family->id
        ]);

        $mappedStudy = $study->map();

        $this->assertSame(1,$mappedStudy['id']);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$mappedStudy['name']);
        $this->assertEquals('Des. Aplicacions Multiplataforma',$mappedStudy['shortname']);
        $this->assertEquals('DAM',$mappedStudy['code']);

        $this->assertEquals($department->id,$mappedStudy['department_id']);
        $this->assertEquals("Departament d'Informàtica",$mappedStudy['department_name']);
        $this->assertEquals('Informàtica',$mappedStudy['department_shortname']);
        $this->assertEquals('INF',$mappedStudy['department_code']);

        $this->assertEquals($family->id,$mappedStudy['family_id']);
        $this->assertEquals('Informàtica',$mappedStudy['family_name']);
        $this->assertEquals('INF',$mappedStudy['family_code']);

        $this->assertNotNull($mappedStudy['created_at']);
        $this->assertNotNull($mappedStudy['updated_at']);
        $this->assertNotNull($mappedStudy['created_at_timestamp']);
        $this->assertNotNull($mappedStudy['updated_at_timestamp']);
        $this->assertNotNull($mappedStudy['formatted_created_at']);
        $this->assertNotNull($mappedStudy['formatted_updated_at']);
        $this->assertNotNull($mappedStudy['formatted_created_at_diff']);
        $this->assertNotNull($mappedStudy['formatted_updated_at_diff']);

    }
}
