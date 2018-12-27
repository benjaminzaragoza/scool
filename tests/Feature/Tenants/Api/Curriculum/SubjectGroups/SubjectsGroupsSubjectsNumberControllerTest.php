<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Studies;

use App\Events\SubjectGroups\SubjectGroupSubjectsNumberUpdated;
use App\Models\Subject;
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
    public function can_update_subject_group_subjects_number_validation()
    {
        $this->loginAsSuperAdmin('api');

        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/subjects_number',[]);
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

        Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $subjectGroup->id,
            'hours' => 79
        ]);

        Subject::firstOrCreate([
            'name' => 'Preparació i distribució d’aplicacions',
            'shortname'=> 'Preparació i distribució d’aplicacions',
            'code' =>  'DAM_MP7_UF2',
            'number' => 2,
            'subject_group_id' => $subjectGroup->id,
            'hours' => 20
        ]);

        $response = $this->json('PUT', '/api/v1/subject_groups/' . $subjectGroup->id . '/subjects_number', [
            'subjects_number' => 1
        ]);
        $response->assertStatus(422);
        $result = json_decode($response->getContent());
        $this->assertEquals('El nombre total de UFs és superior al nombre de UFs ja assignades al MP',$result->message);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_subject_group_subjects_number()
    {
        $this->login('api');
        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/subjects_number',[
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
        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/subjects_number',[
            'subjects_number' => 13
        ]);
        $response->assertStatus(401);
    }
}
