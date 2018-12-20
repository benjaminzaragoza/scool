<?php

namespace Tests\Unit\Tenants\Curriculum;


use App\Models\User;
use App\Models\SubjectGroup;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SubjectGroupTest.
 *
 * @package Tests\Unit
 */
class SubjectGroupTest extends TestCase
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
    public function find_by_code()
    {
        $this->assertNull(SubjectGroup::findByCode('040'));

        $mp_start_date = '2017-09-15';
        $mp_end_date = '2018-06-01';
        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Desenvolupament d’interfícies',
            'name' => 'Desenvolupament d’interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => 1,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => $mp_start_date,
            'end' => $mp_end_date,
            'type' => 'Normal'
        ]);

        $this->assertTrue($group->is(SubjectGroup::findByCode('DAM_MP7')));
    }

    /**
     * @test
     * @group curriculum
     */
    public function map()
    {
        $subjectGroup = create_sample_subject_group();

        $mappedSubjectGroup = $subjectGroup->map();


//            'free_hours' => (int) $this->hours,
//            'week_hours' => (int) $this->hours,
//            'start' => $this->start,
//            'end' => $this->end,
//            'type' => $this->type,

        $this->assertSame(1,$mappedSubjectGroup['id']);
        $this->assertEquals("Desenvolupament d’interfícies",$mappedSubjectGroup['name']);
        $this->assertEquals('Interfícies',$mappedSubjectGroup['shortname']);
        $this->assertEquals('DAM_MP7',$mappedSubjectGroup['code']);
        $this->assertSame(7,$mappedSubjectGroup['number']);
        $this->assertSame(99,$mappedSubjectGroup['hours']);
        $this->assertSame(99,$mappedSubjectGroup['free_hours']);
        $this->assertSame(99,$mappedSubjectGroup['week_hours']);
        $this->assertSame('2017-09-15',$mappedSubjectGroup['start']);
        $this->assertSame('2018-06-01',$mappedSubjectGroup['end']);
        $this->assertEquals('subject_groups',$mappedSubjectGroup['api_uri']);

        $this->assertSame(1,$mappedSubjectGroup['study_id']);
        $this->assertEquals('Desenvolupament Aplicacions Multiplataforma',$mappedSubjectGroup['study_name']);
        $this->assertEquals('Des. Aplicacions Multiplataforma',$mappedSubjectGroup['study_shortname']);
        $this->assertEquals('DAM',$mappedSubjectGroup['study_code']);

        $this->assertSame('Normal',$mappedSubjectGroup['type']);

        $this->assertNotNull($mappedSubjectGroup['created_at']);
        $this->assertNotNull($mappedSubjectGroup['updated_at']);
        $this->assertNotNull($mappedSubjectGroup['created_at_timestamp']);
        $this->assertNotNull($mappedSubjectGroup['updated_at_timestamp']);
        $this->assertNotNull($mappedSubjectGroup['formatted_created_at']);
        $this->assertNotNull($mappedSubjectGroup['formatted_updated_at']);
        $this->assertNotNull($mappedSubjectGroup['formatted_created_at_diff']);
        $this->assertNotNull($mappedSubjectGroup['formatted_updated_at_diff']);

        $this->assertEquals('Desenvolupament d’interfícies Interfícies DAM_MP7',$mappedSubjectGroup['full_search']);

    }
}
