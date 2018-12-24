<?php

namespace Tests\Feature\Api\Subjects\Curriculum;

use App\Events\Subjects\SubjectDeleted;
use App\Events\Subjects\SubjectStored;
use App\Models\Course;
use App\Models\Study;
use App\Models\Subject;
use App\Models\SubjectGroup;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class SubjectsControllerTest.
 *
 * @package Tests\Feature
 */
class SubjectsControllerTest extends BaseTenantTest
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
    public function can_list_subjects()
    {
        initialize_fake_subjects();
        $this->loginAsSuperAdmin('api');

        $response =  $this->json('GET','/api/v1/subjects');
        $response->assertSuccessful();
        $subjects = json_decode($response->getContent());
        $this->assertCount(2,$subjects);
        $this->assertSame(1,$subjects[0]->id);
        $this->assertEquals('Disseny i implementació d’interfícies',$subjects[0]->name);
        $this->assertEquals('Interfícies',$subjects[0]->shortname);
        $this->assertEquals('DAM_MP7_UF1',$subjects[0]->code);
        $this->assertNotNull($subjects[0]->created_at);
        $this->assertNotNull($subjects[0]->created_at_timestamp);
        $this->assertNotNull($subjects[0]->formatted_created_at);
        $this->assertNotNull($subjects[0]->formatted_created_at_diff);
        $this->assertNotNull($subjects[0]->updated_at);
        $this->assertNotNull($subjects[0]->updated_at_timestamp);
        $this->assertNotNull($subjects[0]->formatted_updated_at);
        $this->assertNotNull($subjects[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_list_subjects()
    {
        initialize_fake_subjects();
        $this->loginAsCurriculumManager('api');

        $response =  $this->json('GET','/api/v1/subjects');
        $response->assertSuccessful();
        $subjects = json_decode($response->getContent());
        $this->assertCount(2,$subjects);
        $this->assertSame(1,$subjects[0]->id);
        $this->assertEquals('Disseny i implementació d’interfícies',$subjects[0]->name);
        $this->assertEquals('Interfícies',$subjects[0]->shortname);
        $this->assertEquals('DAM_MP7_UF1',$subjects[0]->code);
        $this->assertNotNull($subjects[0]->created_at);
        $this->assertNotNull($subjects[0]->created_at_timestamp);
        $this->assertNotNull($subjects[0]->formatted_created_at);
        $this->assertNotNull($subjects[0]->formatted_created_at_diff);
        $this->assertNotNull($subjects[0]->updated_at);
        $this->assertNotNull($subjects[0]->updated_at_timestamp);
        $this->assertNotNull($subjects[0]->formatted_updated_at);
        $this->assertNotNull($subjects[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_list_subjects()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/subjects');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_list_subjects()
    {
        $response = $this->json('GET','/api/v1/subjects');
        $response->assertStatus(401);
    }


    /**
     * @test
     * @group curriculum
     */
    public function can_store_subjects()
    {
        $this->loginAsSuperAdmin('api');

        $dam = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
        ]);

        $course2Dam = Course::firstOrCreate([
            'code' => '2DAM',
            'name' => 'Segon Curs Desenvolupament Aplicacions Multiplataforma',
            'order' => 2,
            'study_id' => $dam->id
        ]);

        $subjectGroup = SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $dam->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        Event::fake();
        $response =  $this->json('POST','/api/v1/subjects',$subject = [
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'study_id' => $dam->id,
            'subject_group_id' => $subjectGroup->id,
            'course_id' => $course2Dam->id,
            'type_id' => 1,
            'hours' => 79,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);
        $response->assertSuccessful();
        $createdSubject = json_decode($response->getContent());
        Event::assertDispatched(SubjectStored::class,function ($event) use ($createdSubject){
            return $event->subject->is(Subject::findOrFail($createdSubject->id));
        });
        $this->assertSame($createdSubject->id,1);
        $this->assertEquals($createdSubject->name,'Disseny i implementació d’interfícies');
        $this->assertEquals($createdSubject->shortname,'Interfícies');
        $this->assertEquals($createdSubject->code,'DAM_MP7_UF1');
        $this->assertSame($createdSubject->number,1);
        $this->assertEquals($createdSubject->study_id,$dam->id);
        $this->assertEquals($createdSubject->subject_group_id,1);
        $this->assertEquals($createdSubject->course_id,1);
        $this->assertEquals($createdSubject->hours,79);
        $this->assertEquals($createdSubject->start,'2017-09-15');
        $this->assertEquals($createdSubject->end,'2018-06-01');
        $this->assertNotNull($createdSubject->created_at);
        $this->assertNotNull($createdSubject->created_at_timestamp);
        $this->assertNotNull($createdSubject->formatted_created_at);
        $this->assertNotNull($createdSubject->formatted_created_at_diff);
        $this->assertNotNull($createdSubject->updated_at);
        $this->assertNotNull($createdSubject->updated_at_timestamp);
        $this->assertNotNull($createdSubject->formatted_updated_at);
        $this->assertNotNull($createdSubject->formatted_updated_at_diff);

        try {
            $subject = Subject::findOrFail($createdSubject->id);
        } catch (\Exception $e) {
            $this->fails('UF not found at database!');
        }

        $this->assertSame($subject->id,1);
        $this->assertEquals($subject->name,'Disseny i implementació d’interfícies');
        $this->assertEquals($subject->shortname,'Interfícies');
        $this->assertEquals($subject->code,'DAM_MP7_UF1');
        $this->assertEquals($subject->number,1);
        $this->assertEquals($subject->study_id,$dam->id);
        $this->assertEquals($subject->subject_group_id,1);
        $this->assertEquals($subject->course_id,1);
        $this->assertEquals($subject->hours,79);
        $this->assertEquals($subject->start,'2017-09-15');
        $this->assertEquals($subject->end,'2018-06-01');
        $this->assertNotNull($subject->created_at);
        $this->assertNotNull($subject->created_at_timestamp);
        $this->assertNotNull($subject->formatted_created_at);
        $this->assertNotNull($subject->formatted_created_at_diff);
        $this->assertNotNull($subject->updated_at);
        $this->assertNotNull($subject->updated_at_timestamp);
        $this->assertNotNull($subject->formatted_updated_at);
        $this->assertNotNull($subject->formatted_updated_at_diff);

    }

    /**
     * @test
     * @group curriculum
     */
    public function curriculum_manager_can_store_subjects()
    {
        $this->loginAsCurriculumManager('api');

        $dam = Study::firstOrCreate([
            'name' => 'Desenvolupament Aplicacions Multiplataforma',
            'shortname' => 'Des. Aplicacions Multiplataforma',
            'code' => 'DAM',
        ]);

        $course2Dam = Course::firstOrCreate([
            'code' => '2DAM',
            'name' => 'Segon Curs Desenvolupament Aplicacions Multiplataforma',
            'order' => 2,
            'study_id' => $dam->id
        ]);

        $subjectGroup = SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $dam->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        Event::fake();
        $response =  $this->json('POST','/api/v1/subjects',$subject = [
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'study_id' => $dam->id,
            'subject_group_id' => $subjectGroup->id,
            'course_id' => $course2Dam->id,
            'type_id' => 1,
            'hours' => 79,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);
        $response->assertSuccessful();
        $createdSubject = json_decode($response->getContent());
        Event::assertDispatched(SubjectStored::class,function ($event) use ($createdSubject){
            return $event->subject->is(Subject::findOrFail($createdSubject->id));
        });
        $this->assertSame($createdSubject->id,1);
        $this->assertEquals($createdSubject->name,'Disseny i implementació d’interfícies');
        $this->assertEquals($createdSubject->shortname,'Interfícies');
        $this->assertEquals($createdSubject->code,'DAM_MP7_UF1');
        $this->assertSame($createdSubject->number,1);
        $this->assertEquals($createdSubject->study_id,$dam->id);
        $this->assertEquals($createdSubject->subject_group_id,1);
        $this->assertEquals($createdSubject->course_id,1);
        $this->assertEquals($createdSubject->hours,79);
        $this->assertEquals($createdSubject->start,'2017-09-15');
        $this->assertEquals($createdSubject->end,'2018-06-01');
        $this->assertNotNull($createdSubject->created_at);
        $this->assertNotNull($createdSubject->created_at_timestamp);
        $this->assertNotNull($createdSubject->formatted_created_at);
        $this->assertNotNull($createdSubject->formatted_created_at_diff);
        $this->assertNotNull($createdSubject->updated_at);
        $this->assertNotNull($createdSubject->updated_at_timestamp);
        $this->assertNotNull($createdSubject->formatted_updated_at);
        $this->assertNotNull($createdSubject->formatted_updated_at_diff);

        try {
            $subject = Subject::findOrFail($createdSubject->id);
        } catch (\Exception $e) {
            $this->fails('Study not found at database!');
        }

        $this->assertSame($subject->id,1);
        $this->assertEquals($subject->name,'Disseny i implementació d’interfícies');
        $this->assertEquals($subject->shortname,'Interfícies');
        $this->assertEquals($subject->code,'DAM_MP7_UF1');
        $this->assertEquals($subject->number,1);
        $this->assertEquals($subject->study_id,$dam->id);
        $this->assertEquals($subject->subject_group_id,1);
        $this->assertEquals($subject->course_id,1);
        $this->assertEquals($subject->hours,79);
        $this->assertEquals($subject->start,'2017-09-15');
        $this->assertEquals($subject->end,'2018-06-01');
        $this->assertNotNull($subject->created_at);
        $this->assertNotNull($subject->created_at_timestamp);
        $this->assertNotNull($subject->formatted_created_at);
        $this->assertNotNull($subject->formatted_created_at_diff);
        $this->assertNotNull($subject->updated_at);
        $this->assertNotNull($subject->updated_at_timestamp);
        $this->assertNotNull($subject->formatted_updated_at);
        $this->assertNotNull($subject->formatted_updated_at_diff);

    }

    /**
     * @test
     * @group curriculum
     */
    public function can_store_subjects_validation()
    {
        $this->loginAsSuperAdmin('api');
        $response = $this->json('POST', '/api/v1/subjects', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_delete_subjects()
    {
        $this->loginAsSuperAdmin('api');

        $subject = create_sample_subject();
        Event::fake();
        $response = $this->json('DELETE','/api/v1/subjects/' . $subject->id);
        Event::assertDispatched(SubjectDeleted::class,function ($event) use ($subject){
            return $event->oldSubject['id'] === $subject->id;
        });

        $response->assertSuccessful();
        $subject = $subject->fresh();
        $this->assertNull($subject);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_delete_subjects()
    {
        $this->loginAsCurriculumManager('api');

        $subject = create_sample_subject();
        Event::fake();
        $response = $this->json('DELETE','/api/v1/subjects/' . $subject->id);
        Event::assertDispatched(SubjectDeleted::class,function ($event) use ($subject){
            return $event->oldSubject['id'] === $subject->id;
        });

        $response->assertSuccessful();
        $subject = $subject->fresh();
        $this->assertNull($subject);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_delete_subjects()
    {
        $this->login('api');

        $subject = create_sample_subject();
        $response = $this->json('DELETE','/api/v1/subjects/' . $subject->id);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_delete_subjects()
    {
        $subject = create_sample_subject();
        $response = $this->json('DELETE','/api/v1/subjects/' . $subject->id);
        $response->assertStatus(401);
    }
}
