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
     */
    public function addTag()
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

        $tag = StudyTag::create([
            'value' => 'Tag1',
            'description' => 'Tag 1 bla bla bla',
            'color' => '#453423'
        ]);
        $this->assertCount(0,$study->tags);
        $study->addTag($tag);
        $study = $study->fresh();
        $this->assertCount(1,$study->tags);
        $this->assertTrue($study->tags[0]->is($tag));
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
            'family_id' => $family->id,
            'subjects_number' => 14,
            'subject_groups_number' => 33
        ]);

        $mappedStudy = $study->map();

        $this->assertSame(1,$mappedStudy['id']);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$mappedStudy['name']);
        $this->assertEquals('Des. Aplicacions Multiplataforma',$mappedStudy['shortname']);
        $this->assertEquals('DAM',$mappedStudy['code']);
        $this->assertEquals(14,$mappedStudy['subjects_number']);
        $this->assertEquals(33,$mappedStudy['subject_groups_number']);

        $this->assertEquals('studies',$mappedStudy['api_uri']);

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

        // TAGS
        $tag1 = StudyTag::create([
            'value' => 'Tag1',
            'description' => 'Tag 1 bla bla bla',
            'color' => '#453423'
        ]);
        $tag2 = StudyTag::create([
            'value' => 'Tag2',
            'description' => 'Tag 2 bla bla bla',
            'color' => '#223423'
        ]);
        $tag3 = StudyTag::create([
            'value' => 'Tag3',
            'description' => 'Tag 3 bla bla bla',
            'color' => '#333423'
        ]);
        $study->addTag($tag1);
        $study->addTag($tag2);
        $study->addTag($tag3);

        $study= $study->fresh();
        $mappedStudy = $study->map();
        $this->assertCount(3, $mappedStudy['tags']);
        $this->assertEquals('Tag1',$mappedStudy['tags'][0]['value']);
        $this->assertEquals('Tag 1 bla bla bla',$mappedStudy['tags'][0]['description']);
        $this->assertEquals('#453423',$mappedStudy['tags'][0]['color']);

        $this->assertEquals('Tag2',$mappedStudy['tags'][1]['value']);
        $this->assertEquals('Tag 2 bla bla bla',$mappedStudy['tags'][1]['description']);
        $this->assertEquals('#223423',$mappedStudy['tags'][1]['color']);

        $this->assertEquals('Tag3',$mappedStudy['tags'][2]['value']);
        $this->assertEquals('Tag 3 bla bla bla',$mappedStudy['tags'][2]['description']);
        $this->assertEquals('#333423',$mappedStudy['tags'][2]['color']);

        $this->assertEquals('Desenvolupament d’interfícies Interfícies DAM_MP7',$mappedStudy['full_search']);

    }
}
