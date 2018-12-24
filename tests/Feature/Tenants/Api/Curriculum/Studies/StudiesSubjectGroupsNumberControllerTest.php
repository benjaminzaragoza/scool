<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Studies;

use App\Events\Studies\StudySubjectGroupsNumberUpdated;
use App\Models\SubjectGroup;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class StudiesSubjectGroupsNumberControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class StudiesSubjectGroupsNumberControllerTest extends BaseTenantTest {

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
    public function can_update_study_SubjectGroupsNumber()
    {
        $this->loginAsSuperAdmin('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/subject_groups_number',[
            'subject_groups_number' => 13
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(StudySubjectGroupsNumberUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals(13,$result->subject_groups_number);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->subject_groups_number,13);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_update_study_SubjectGroupsNumber()
    {
        $this->loginAsCurriculumManager('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/subject_groups_number',[
            'subject_groups_number' => 13
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(StudySubjectGroupsNumberUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals(13,$result->subject_groups_number);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->subject_groups_number,13);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_update_study_SubjectGroupsNumber_validation()
    {
        $this->loginAsSuperAdmin('api');

        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/subject_groups_number',[]);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group curriculum
     */
    public function can_update_study_SubjectGroupsNumber_validation_can_be_more_than_subject_groups()
    {
        $this->loginAsSuperAdmin('api');

        $study = create_sample_study();

        SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'description' => 'Bla bla bla',
            'code' => 'DAM_MP7',
            'number' => 7,
            'study_id' => $study->id,
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
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01'
        ]);

        $response = $this->json('PUT', '/api/v1/studies/' . $study->id . '/subject_groups_number', [
            'subject_groups_number' => 1
        ]);
        $response->assertStatus(422);
        $result = json_decode($response->getContent());
        $this->assertEquals('El nombre total de MPS és superior al nombre de MPs ja assignades al estudi',$result->message);
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_study_SubjectGroupsNumber()
    {
        $this->login('api');
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/subject_groups_number',[
            'subject_groups_number' => 13
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_update_study_SubjectGroupsNumber()
    {
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/subject_groups_number',[
            'subject_groups_number' => 13
        ]);
        $response->assertStatus(401);
    }
}
