<?php

namespace Tests\Unit\Tenants\Curriculum;


use App\Models\SubjectGroupTag;
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
            'end' => $mp_end_date
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

        $this->assertSame(1,$mappedSubjectGroup['id']);
        $this->assertEquals("Desenvolupament d’interfícies",$mappedSubjectGroup['name']);
        $this->assertEquals('Interfícies',$mappedSubjectGroup['shortname']);
        $this->assertEquals('DAM_MP7',$mappedSubjectGroup['code']);
        $this->assertEquals('Bla bla bla',$mappedSubjectGroup['description']);
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

        $this->assertNotNull($mappedSubjectGroup['created_at']);
        $this->assertNotNull($mappedSubjectGroup['updated_at']);
        $this->assertNotNull($mappedSubjectGroup['created_at_timestamp']);
        $this->assertNotNull($mappedSubjectGroup['updated_at_timestamp']);
        $this->assertNotNull($mappedSubjectGroup['formatted_created_at']);
        $this->assertNotNull($mappedSubjectGroup['formatted_updated_at']);
        $this->assertNotNull($mappedSubjectGroup['formatted_created_at_diff']);
        $this->assertNotNull($mappedSubjectGroup['formatted_updated_at_diff']);

        $this->assertEquals('Desenvolupament d’interfícies Interfícies DAM_MP7',$mappedSubjectGroup['full_search']);

        // TAGS
        $tag1 = SubjectGroupTag::create([
            'value' => 'Tag1',
            'description' => 'Tag 1 bla bla bla',
            'color' => '#453423'
        ]);
        $tag2 = SubjectGroupTag::create([
            'value' => 'Tag2',
            'description' => 'Tag 2 bla bla bla',
            'color' => '#223423'
        ]);
        $tag3 = SubjectGroupTag::create([
            'value' => 'Tag3',
            'description' => 'Tag 3 bla bla bla',
            'color' => '#333423'
        ]);
        $subjectGroup->addTag($tag1);
        $subjectGroup->addTag($tag2);
        $subjectGroup->addTag($tag3);

        $subjectGroup= $subjectGroup->fresh();
        $mappedSubjectGroup = $subjectGroup->map();
        $this->assertCount(3, $mappedSubjectGroup['tags']);
        $this->assertEquals('Tag1',$mappedSubjectGroup['tags'][0]['value']);
        $this->assertEquals('Tag 1 bla bla bla',$mappedSubjectGroup['tags'][0]['description']);
        $this->assertEquals('#453423',$mappedSubjectGroup['tags'][0]['color']);

        $this->assertEquals('Tag2',$mappedSubjectGroup['tags'][1]['value']);
        $this->assertEquals('Tag 2 bla bla bla',$mappedSubjectGroup['tags'][1]['description']);
        $this->assertEquals('#223423',$mappedSubjectGroup['tags'][1]['color']);

        $this->assertEquals('Tag3',$mappedSubjectGroup['tags'][2]['value']);
        $this->assertEquals('Tag 3 bla bla bla',$mappedSubjectGroup['tags'][2]['description']);
        $this->assertEquals('#333423',$mappedSubjectGroup['tags'][2]['color']);

    }

    /**
     * @test
     */
    public function addTag()
    {
        $subjectGroup = create_sample_subject_group();

        $tag = SubjectGroupTag::create([
            'value' => 'Tag1',
            'description' => 'Tag 1 bla bla bla',
            'color' => '#453423'
        ]);
        $this->assertCount(0,$subjectGroup->tags);
        $subjectGroup->addTag($tag);
        $subjectGroup = $subjectGroup->fresh();
        $this->assertCount(1,$subjectGroup->tags);
        $this->assertTrue($subjectGroup->tags[0]->is($tag));
    }
}
