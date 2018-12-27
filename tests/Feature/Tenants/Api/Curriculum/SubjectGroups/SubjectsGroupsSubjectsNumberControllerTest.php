<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Studies;

use App\Events\SubjectGroups\SubjectGroupSubjectsNumberUpdated;
use App\Models\SubjectGroup;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class SubjectsGroupsSubjectsNumberControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class SubjectsGroupsSubjectsNumberControllerTest extends BaseTenantTest {

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
    public function can_update_subject_group_subjects_number()
    {
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin('api');

        $subjectGroup = create_sample_subject_group();

        Event::fake();

        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/subjects_number',[
            'subjects_number' => 5
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(SubjectGroupSubjectsNumberUpdated::class,function ($event) use ($subjectGroup){
            return $event->subjectGroup->is($subjectGroup);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals(5,$result->subjects_number);
        $this->assertEquals($result->id,$subjectGroup->id);

        $subjectGroup = $subjectGroup->fresh();
        $this->assertEquals($subjectGroup->subjects_number,5);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_update_subject_group_subjects_number()
    {
        $this->loginAsCurriculumManager('api');

        $subjectGroup = create_sample_subject_group();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $subjectGroup->id . '/subjects_number',[
            'subjects_number' => 13
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(Studysubjects_numberUpdated::class,function ($event) use ($subjectGroup){
            return $event->subject_group->is($subjectGroup);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals(13,$result->subjects_number);
        $this->assertEquals($result->id,$subjectGroup->id);

        $subjectGroup = $subjectGroup->fresh();
        $this->assertEquals($subjectGroup->subjects_number,13);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_update_subject_group_subjects_number_validation()
    {
        $this->loginAsSuperAdmin('api');

        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/studies/' . $subjectGroup->id . '/subjects_number',[]);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_update_subject_group_subjects_number_validation_can_be_more_than_subject_groups()
    {
        $this->loginAsSuperAdmin('api');

        $subjectGroup = create_sample_subject_group();

        SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'description' => 'Bla bla bla',
            'code' => 'DAM_MP7',
            'number' => 7,
            'subject_group_id' => $subjectGroup->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        SubjectGroup::firstOrCreate([
            'name' => 'MP8',
            'shortname' => 'MP8',
            'description' => 'Bla bla bla',
            'code' => 'DAM_MP8',
            'number' => 8,
            'subject_group_id' => $subjectGroup->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        $response = $this->json('PUT', '/api/v1/studies/' . $subjectGroup->id . '/subjects_number', [
            'subjects_number' => 1
        ]);
        $response->assertStatus(422);
        $result = json_decode($response->getContent());
        $this->assertEquals('El nombre total de MPS és superior al nombre de MPs ja assignades al estudi',$result->message);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_subject_group_subjects_number()
    {
        $this->login('api');
        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/studies/' . $subjectGroup->id . '/subjects_number',[
            'subjects_number' => 13
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_update_subject_group_subjects_number()
    {
        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/studies/' . $subjectGroup->id . '/subjects_number',[
            'subjects_number' => 13
        ]);
        $response->assertStatus(401);
    }
}
