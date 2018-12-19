<?php

namespace Tests\Unit\Tenants\Curriculum;


use App\Models\User;
use App\Models\Subject;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SubjectTest.
 *
 * @package Tests\Unit
 */
class SubjectTest extends TestCase
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
     * @curriculum
     */
    public function find_by_code()
    {
        $this->assertNull(Subject::findByCode('040'));

        $mp_start_date = '2017-09-15';
        $mp_end_date = '2018-06-01';
        $subject = Subject::create([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Disseny i implementació d’interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'subject_group_id' => 1,
            'study_id' => 1,
            'course_id' => 1,
            'type_id' => 1,
            'hours' => 79,
            'start' => $mp_start_date,
            'end' => $mp_end_date
        ]);

        $this->assertTrue($subject->is(Subject::findByCode('DAM_MP7_UF1')));
    }

    /**
     * @test
     * @group curriculum
     */
    public function map()
    {
        $subject = create_sample_subject();

        $mappedSubject = $subject->map();

        $this->assertSame(1,$mappedSubject['id']);
        $this->assertEquals("Disseny i implementació d’interfícies",$mappedSubject['name']);
        $this->assertEquals('Interfícies',$mappedSubject['shortname']);
        $this->assertEquals('DAM_MP7_UF1',$mappedSubject['code']);
        $this->assertSame(1,$mappedSubject['number']);
        $this->assertSame(79,$mappedSubject['hours']);
        $this->assertSame('2017-09-15',$mappedSubject['start']);
        $this->assertEquals('subjects',$mappedSubject['api_uri']);

        $this->assertEquals(1,$mappedSubject['subject_group_id']);
        $this->assertEquals('Desenvolupament d’interfícies',$mappedSubject['subject_group_name']);
        $this->assertEquals('Interfícies',$mappedSubject['subject_group_shortname']);
        $this->assertEquals('DAM_MP7',$mappedSubject['subject_group_code']);
        $this->assertSame(7,$mappedSubject['subject_group_number']);
        $this->assertSame(99,$mappedSubject['subject_group_hours']);
        $this->assertSame(0,$mappedSubject['subject_group_free_hours']);
        $this->assertEquals(3,$mappedSubject['subject_group_week_hours']);
        $this->assertEquals('2017-09-15',$mappedSubject['subject_group_start']);
        $this->assertEquals('2018-06-01',$mappedSubject['subject_group_end']);
        $this->assertEquals('Normal',$mappedSubject['subject_group_type']);

        $this->assertSame(1,$mappedSubject['study_id']);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$mappedSubject['study_name']);
        $this->assertEquals('Des. Aplicacions Multiplataforma',$mappedSubject['study_shortname']);
        $this->assertEquals('DAM',$mappedSubject['study_code']);

        $this->assertSame(1,$mappedSubject['course_id']);
        $this->assertEquals('Segon Curs Desenvolupament Aplicacions Multiplataforma', $mappedSubject['course_name']);
        $this->assertEquals('2DAM',$mappedSubject['course_code']);
        $this->assertSame(2,$mappedSubject['course_order']);

        $this->assertSame(1,$mappedSubject['type_id']);

        $this->assertNotNull($mappedSubject['created_at']);
        $this->assertNotNull($mappedSubject['updated_at']);
        $this->assertNotNull($mappedSubject['created_at_timestamp']);
        $this->assertNotNull($mappedSubject['updated_at_timestamp']);
        $this->assertNotNull($mappedSubject['formatted_created_at']);
        $this->assertNotNull($mappedSubject['formatted_updated_at']);
        $this->assertNotNull($mappedSubject['formatted_created_at_diff']);
        $this->assertNotNull($mappedSubject['formatted_updated_at_diff']);


    }
}
