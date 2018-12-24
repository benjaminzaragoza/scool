<?php

namespace Tests\Feature\Api\Subjects\Curriculum;

use App\Events\SubjectGroups\SubjectGroupDeleted;
use App\Events\SubjectGroups\SubjectGroupStored;
use App\Models\Study;
use App\Models\SubjectGroup;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class SubjectGroupsControllerTest.
 *
 * @package Tests\Feature
 */
class SubjectGroupsControllerTest extends BaseTenantTest
{
    use RefreshDatabase, CanLogin;

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
    public function can_list_subject_groups()
    {
        initialize_fake_subjectGroups();
        $this->loginAsSuperAdmin('api');

        $response =  $this->json('GET','/api/v1/subject_groups');
        $response->assertSuccessful();
        $subjectGroups = json_decode($response->getContent());
        $this->assertCount(3,$subjectGroups);
        $this->assertSame(1,$subjectGroups[0]->id);
        $this->assertEquals('Desenvolupament d’interfícies',$subjectGroups[0]->name);
        $this->assertEquals('Interfícies',$subjectGroups[0]->shortname);
        $this->assertEquals('DAM_MP7',$subjectGroups[0]->code);
        $this->assertEquals('Operacions de compravenda',$subjectGroups[1]->name);
        $this->assertEquals('Operacions de compravenda',$subjectGroups[1]->shortname);
        $this->assertEquals('GAD_MP2',$subjectGroups[1]->code);
        $this->assertEquals('Operacions de recursos humans',$subjectGroups[2]->name);
        $this->assertEquals('Operacions de recursos humans',$subjectGroups[2]->shortname);
        $this->assertEquals('GAD_MP3',$subjectGroups[2]->code);
        $this->assertNotNull($subjectGroups[0]->created_at);
        $this->assertNotNull($subjectGroups[0]->created_at_timestamp);
        $this->assertNotNull($subjectGroups[0]->formatted_created_at);
        $this->assertNotNull($subjectGroups[0]->formatted_created_at_diff);
        $this->assertNotNull($subjectGroups[0]->updated_at);
        $this->assertNotNull($subjectGroups[0]->updated_at_timestamp);
        $this->assertNotNull($subjectGroups[0]->formatted_updated_at);
        $this->assertNotNull($subjectGroups[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_list_subjectGroups()
    {
        initialize_fake_subjectGroups();
        $this->loginAsCurriculumManager('api');

        $response =  $this->json('GET','/api/v1/subject_groups');
        $response->assertSuccessful();
        $subjectGroups = json_decode($response->getContent());
        $this->assertCount(3,$subjectGroups);
        $this->assertSame(1,$subjectGroups[0]->id);
        $this->assertEquals('Desenvolupament d’interfícies',$subjectGroups[0]->name);
        $this->assertEquals('Interfícies',$subjectGroups[0]->shortname);
        $this->assertEquals('DAM_MP7',$subjectGroups[0]->code);
        $this->assertEquals('Operacions de compravenda',$subjectGroups[1]->name);
        $this->assertEquals('Operacions de compravenda',$subjectGroups[1]->shortname);
        $this->assertEquals('GAD_MP2',$subjectGroups[1]->code);
        $this->assertEquals('Operacions de recursos humans',$subjectGroups[2]->name);
        $this->assertEquals('Operacions de recursos humans',$subjectGroups[2]->shortname);
        $this->assertEquals('GAD_MP3',$subjectGroups[2]->code);
        $this->assertNotNull($subjectGroups[0]->created_at);
        $this->assertNotNull($subjectGroups[0]->created_at_timestamp);
        $this->assertNotNull($subjectGroups[0]->formatted_created_at);
        $this->assertNotNull($subjectGroups[0]->formatted_created_at_diff);
        $this->assertNotNull($subjectGroups[0]->updated_at);
        $this->assertNotNull($subjectGroups[0]->updated_at_timestamp);
        $this->assertNotNull($subjectGroups[0]->formatted_updated_at);
        $this->assertNotNull($subjectGroups[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_list_subjectGroups()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/subject_groups');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_list_subjectGroups()
    {
        $response = $this->json('GET','/api/v1/subject_groups');
        $response->assertStatus(401);
    }


    /**
     * @test
     * @group curriculum
     */
    public function can_store_subjectGroups()
    {
        $this->withoutExceptionHandling();
        initialize_subject_group_tags();
        $this->loginAsSuperAdmin('api');

        $dam = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
        ]);

        Event::fake();
        $response =  $this->json('POST','/api/v1/subject_groups',$subjectGroup = [
            'number' => 7,
            'code' =>  'DAM_MP7',
            'name' => 'Full name MP7',
            'shortname'=> 'Shortname MP7',
            'description' => 'Bla bla bla',
            'study_id' => $dam->id,
            'hours' => 75,
            'free_hours' => 0,
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01',
            'tags' => [1]
        ]);
        $response->assertSuccessful();
        $createdSubjectGroup = json_decode($response->getContent());
        Event::assertDispatched(SubjectGroupStored::class,function ($event) use ($createdSubjectGroup){
            return $event->subjectGroup->is(SubjectGroup::findOrFail($createdSubjectGroup->id));
        });
        $this->assertSame($createdSubjectGroup->id,1);
        $this->assertEquals($createdSubjectGroup->name,'Full name MP7');
        $this->assertEquals($createdSubjectGroup->shortname,'Shortname MP7');
        $this->assertEquals($createdSubjectGroup->description,'Bla bla bla');
        $this->assertEquals($createdSubjectGroup->code,'DAM_MP7');
        $this->assertSame($createdSubjectGroup->number,7);
        $this->assertEquals($createdSubjectGroup->study_id,$dam->id);
        $this->assertEquals($createdSubjectGroup->hours,75);
        $this->assertEquals($createdSubjectGroup->free_hours,75);
        $this->assertEquals($createdSubjectGroup->week_hours,75);
        $this->assertEquals($createdSubjectGroup->start,'2017-09-15');
        $this->assertEquals($createdSubjectGroup->end,'2018-06-01');
        $this->assertNotNull($createdSubjectGroup->created_at);
        $this->assertNotNull($createdSubjectGroup->created_at_timestamp);
        $this->assertNotNull($createdSubjectGroup->formatted_created_at);
        $this->assertNotNull($createdSubjectGroup->formatted_created_at_diff);
        $this->assertNotNull($createdSubjectGroup->updated_at);
        $this->assertNotNull($createdSubjectGroup->updated_at_timestamp);
        $this->assertNotNull($createdSubjectGroup->formatted_updated_at);
        $this->assertNotNull($createdSubjectGroup->formatted_updated_at_diff);

        $this->assertNotNull($createdSubjectGroup->tags);
        $this->assertCount(1,$createdSubjectGroup->tags);
        $this->assertEquals('Normal',$createdSubjectGroup->tags[0]->value);
        $this->assertEquals('Mòdul normal',$createdSubjectGroup->tags[0]->description);
        $this->assertEquals('amber',$createdSubjectGroup->tags[0]->color);
        $this->assertNull($createdSubjectGroup->tags[0]->icon);

        try {
            $subjectGroup = SubjectGroup::findOrFail($createdSubjectGroup->id);
        } catch (\Exception $e) {
            $this->fails('MP not found at database!');
        }

        $this->assertSame($subjectGroup->id,1);
        $this->assertEquals($subjectGroup->name,'Full name MP7');
        $this->assertEquals($subjectGroup->shortname,'Shortname MP7');
        $this->assertEquals($subjectGroup->description,'Bla bla bla');
        $this->assertEquals($subjectGroup->code,'DAM_MP7');
        $this->assertEquals($subjectGroup->number,7);
        $this->assertEquals($subjectGroup->study_id,$dam->id);
        $this->assertEquals($subjectGroup->hours,75);
        $this->assertEquals($subjectGroup->free_hours,0);
        $this->assertEquals($subjectGroup->week_hours,3);
        $this->assertEquals($subjectGroup->start,'2017-09-15');
        $this->assertEquals($subjectGroup->end,'2018-06-01');

        $this->assertNotNull($subjectGroup->created_at);
        $this->assertNotNull($subjectGroup->created_at_timestamp);
        $this->assertNotNull($subjectGroup->formatted_created_at);
        $this->assertNotNull($subjectGroup->formatted_created_at_diff);
        $this->assertNotNull($subjectGroup->updated_at);
        $this->assertNotNull($subjectGroup->updated_at_timestamp);
        $this->assertNotNull($subjectGroup->formatted_updated_at);
        $this->assertNotNull($subjectGroup->formatted_updated_at_diff);

    }

    /**
     * @test
     * @group curriculum
     */
    public function curriculum_manager_can_store_subjectGroups()
    {
        $this->loginAsCurriculumManager('api');

        $dam = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
        ]);

        Event::fake();
        $response =  $this->json('POST','/api/v1/subject_groups',$subjectGroup = [
            'number' => 7,
            'code' =>  'DAM_MP7',
            'name' => 'Full name MP7',
            'shortname'=> 'Shortname MP7',
            'description' => 'Bla bla bla',
            'study_id' => $dam->id,
            'hours' => 75,
            'free_hours' => 0,
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);
        $response->assertSuccessful();
        $createdSubjectGroup = json_decode($response->getContent());
        Event::assertDispatched(SubjectGroupStored::class,function ($event) use ($createdSubjectGroup){
            return $event->subjectGroup->is(SubjectGroup::findOrFail($createdSubjectGroup->id));
        });
        $this->assertSame($createdSubjectGroup->id,1);
        $this->assertEquals($createdSubjectGroup->name,'Full name MP7');
        $this->assertEquals($createdSubjectGroup->shortname,'Shortname MP7');
        $this->assertEquals($createdSubjectGroup->description,'Bla bla bla');
        $this->assertEquals($createdSubjectGroup->code,'DAM_MP7');
        $this->assertSame($createdSubjectGroup->number,7);
        $this->assertEquals($createdSubjectGroup->study_id,$dam->id);
        $this->assertEquals($createdSubjectGroup->hours,75);
        $this->assertEquals($createdSubjectGroup->free_hours,75);
        $this->assertEquals($createdSubjectGroup->week_hours,75);
        $this->assertEquals($createdSubjectGroup->start,'2017-09-15');
        $this->assertEquals($createdSubjectGroup->end,'2018-06-01');
        $this->assertNotNull($createdSubjectGroup->created_at);
        $this->assertNotNull($createdSubjectGroup->created_at_timestamp);
        $this->assertNotNull($createdSubjectGroup->formatted_created_at);
        $this->assertNotNull($createdSubjectGroup->formatted_created_at_diff);
        $this->assertNotNull($createdSubjectGroup->updated_at);
        $this->assertNotNull($createdSubjectGroup->updated_at_timestamp);
        $this->assertNotNull($createdSubjectGroup->formatted_updated_at);
        $this->assertNotNull($createdSubjectGroup->formatted_updated_at_diff);

        try {
            $subjectGroup = SubjectGroup::findOrFail($createdSubjectGroup->id);
        } catch (\Exception $e) {
            $this->fails('MP not found at database!');
        }

        $this->assertSame($subjectGroup->id,1);
        $this->assertEquals($subjectGroup->name,'Full name MP7');
        $this->assertEquals($subjectGroup->shortname,'Shortname MP7');
        $this->assertEquals($subjectGroup->description,'Bla bla bla');
        $this->assertEquals($subjectGroup->code,'DAM_MP7');
        $this->assertEquals($subjectGroup->number,7);
        $this->assertEquals($subjectGroup->study_id,$dam->id);
        $this->assertEquals($subjectGroup->hours,75);
        $this->assertEquals($subjectGroup->free_hours,0);
        $this->assertEquals($subjectGroup->week_hours,3);
        $this->assertEquals($subjectGroup->start,'2017-09-15');
        $this->assertEquals($subjectGroup->end,'2018-06-01');

        $this->assertNotNull($subjectGroup->created_at);
        $this->assertNotNull($subjectGroup->created_at_timestamp);
        $this->assertNotNull($subjectGroup->formatted_created_at);
        $this->assertNotNull($subjectGroup->formatted_created_at_diff);
        $this->assertNotNull($subjectGroup->updated_at);
        $this->assertNotNull($subjectGroup->updated_at_timestamp);
        $this->assertNotNull($subjectGroup->formatted_updated_at);
        $this->assertNotNull($subjectGroup->formatted_updated_at_diff);

    }

    /**
     * @test
     * @group curriculum
     */
    public function can_store_subjectGroups_validation()
    {
        $this->loginAsSuperAdmin('api');
        $response = $this->json('POST', '/api/v1/subject_groups', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_delete_subjectGroups()
    {
        $this->loginAsSuperAdmin('api');

        $subjectGroup = create_sample_subject_group();
        Event::fake();
        $response = $this->json('DELETE','/api/v1/subject_groups/' . $subjectGroup->id);
        Event::assertDispatched(SubjectGroupDeleted::class,function ($event) use ($subjectGroup){
            return $event->oldSubjectGroup['id'] === $subjectGroup->id;
        });

        $response->assertSuccessful();
        $subjectGroup = $subjectGroup->fresh();
        $this->assertNull($subjectGroup);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_delete_subjectGroups()
    {
        $this->loginAsCurriculumManager('api');

        $subjectGroup = create_sample_subject_group();
        Event::fake();
        $response = $this->json('DELETE','/api/v1/subject_groups/' . $subjectGroup->id);
        Event::assertDispatched(SubjectGroupDeleted::class,function ($event) use ($subjectGroup){
            return $event->oldSubjectGroup['id'] === $subjectGroup->id;
        });

        $response->assertSuccessful();
        $subjectGroup = $subjectGroup->fresh();
        $this->assertNull($subjectGroup);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_delete_subjectGroups()
    {
        $this->login('api');

        $subject = create_sample_subject();
        $response = $this->json('DELETE','/api/v1/subject_groups/' . $subject->id);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_delete_subjectGroups()
    {
        $subject = create_sample_subject();
        $response = $this->json('DELETE','/api/v1/subject_groups/' . $subject->id);
        $response->assertStatus(401);
    }
}
