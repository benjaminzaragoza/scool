<?php

namespace Tests\Unit\Tenants;

use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\StudyTag;
use App\Models\Subject;
use App\Models\SubjectGroup;
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
    public function assignSubjectGroup()
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

        $this->assertCount(0,$study->subjectGroups);

        $subjectGroup = SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        $study->assignSubjectGroup($subjectGroup);

        $study = $study->fresh();
        $this->assertCount(1,$study->subjectGroups);

        $this->assertTrue($study->subjectGroups[0]->is($subjectGroup));

    }

    /**
     * @test
     * @group curriculum
     */
    public function assignSubject()
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

        $this->assertCount(0,$study->subjectGroups);

        $subjectGroup = SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        $subject = Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Disseny i implementació d’interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $subjectGroup->id,
            'study_id' => $study->id,
            'hours' => 79,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        $study->assignSubject($subject);

        $study = $study->fresh();
        $this->assertCount(1,$study->subjects);

        $this->assertTrue($study->subjects[0]->is($subject));

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

        $subjectGroup = SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        $study->assignSubjectGroup($subjectGroup);

        $mappedStudy = $study->map();

        $this->assertSame(1,$mappedStudy['id']);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$mappedStudy['name']);
        $this->assertEquals('desenvolupament-aplicacions-multiplataforma',$mappedStudy['slug']);
        $this->assertEquals('Des. Aplicacions Multiplataforma',$mappedStudy['shortname']);
        $this->assertEquals('DAM',$mappedStudy['code']);
        $this->assertEquals(14,$mappedStudy['subjects_number']);
        $this->assertEquals(33,$mappedStudy['subject_groups_number']);
        $this->assertEquals(false,$mappedStudy['completed']);

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

        $this->assertCount(1, $mappedStudy['subjectGroups']);
        $this->assertEquals('Desenvolupament d’interfícies',$mappedStudy['subjectGroups'][0]['name']);
        $this->assertEquals('Interfícies',$mappedStudy['subjectGroups'][0]['shortname']);
        $this->assertEquals('DAM_MP7',$mappedStudy['subjectGroups'][0]['code']);

        $this->assertCount(1, $mappedStudy['subjects']);
        $this->assertEquals('Desenvolupament d’interfícies',$mappedStudy['subjects'][0]['name']);
        $this->assertEquals('Interfícies',$mappedStudy['subjects'][0]['shortname']);
        $this->assertEquals('DAM_MP7',$mappedStudy['subjects'][0]['code']);


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

        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma Des. Aplicacions Multiplataforma DAM',$mappedStudy['full_search']);

    }

    /**
     * @test
     * @group curriculum
     */
    public function mapSimple()
    {

        $study = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
            'subjects_number' => 14,
            'subject_groups_number' => 33
        ]);

        $mappedStudy = $study->mapSimple();

        $this->assertSame(1,$mappedStudy['id']);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$mappedStudy['name']);
        $this->assertEquals('desenvolupament-aplicacions-multiplataforma',$mappedStudy['slug']);
        $this->assertEquals('Des. Aplicacions Multiplataforma',$mappedStudy['shortname']);
        $this->assertEquals('DAM',$mappedStudy['code']);
        $this->assertEquals(14,$mappedStudy['subjects_number']);
        $this->assertEquals(33,$mappedStudy['subject_groups_number']);
        $this->assertEquals(false,$mappedStudy['completed']);

        $this->assertEquals('studies',$mappedStudy['api_uri']);

        $this->assertNotNull($mappedStudy['created_at']);
        $this->assertNotNull($mappedStudy['updated_at']);
        $this->assertNotNull($mappedStudy['created_at_timestamp']);
        $this->assertNotNull($mappedStudy['updated_at_timestamp']);
        $this->assertNotNull($mappedStudy['formatted_created_at']);
        $this->assertNotNull($mappedStudy['formatted_updated_at']);
        $this->assertNotNull($mappedStudy['formatted_created_at_diff']);
        $this->assertNotNull($mappedStudy['formatted_updated_at_diff']);

        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma Des. Aplicacions Multiplataforma DAM',$mappedStudy['full_search']);

    }

    /**
     * @test
     * @group curriculum
     */
    public function completed()
    {
        $study = Study::create([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
            'subjects_number' => 3,
            'subject_groups_number' => 2
        ]);
        $this->assertFalse($study->completed);

        $mp1 = SubjectGroup::firstOrCreate([
            'shortname' => 'Desenvolupament d’interfícies',
            'name' => 'Desenvolupament d’interfícies',
            'code' =>  'DAM_MP1',
            'number' => 1,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3
        ]);
        $study = $study->fresh();
        $this->assertFalse($study->completed);

        $mp2 =SubjectGroup::firstOrCreate([
            'shortname' => 'Desenvolupament d’interfícies2',
            'name' => 'Desenvolupament d’interfícies2',
            'code' =>  'DAM_MP2',
            'number' => 2,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3
        ]);
        $study = $study->fresh();
        $this->assertFalse($study->completed);

        Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Disseny i implementació d’interfícies',
            'code' =>  'DAM_MP1_UF1',
            'number' => 1,
            'subject_group_id' => $mp1->id,
            'study_id' => $study->id,
            'hours' => 79,
        ]);
        $study = $study->fresh();
        $this->assertFalse($study->completed);

        Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfícies2',
            'shortname'=> 'Disseny i implementació d’interfícies2',
            'code' =>  'DAM_MP1_UF2',
            'number' => 2,
            'subject_group_id' => $mp1->id,
            'study_id' => $study->id,
            'hours' => 79,
        ]);
        $study = $study->fresh();
        $this->assertFalse($study->completed);

        Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfície3',
            'shortname'=> 'Disseny i implementació d’interfície3',
            'code' =>  'DAM_MP2_UF1',
            'number' => 1,
            'subject_group_id' => $mp2->id,
            'study_id' => $study->id,
            'hours' => 79,
        ]);
        $study = $study->fresh();
        $this->assertTrue($study->completed);
    }
}
