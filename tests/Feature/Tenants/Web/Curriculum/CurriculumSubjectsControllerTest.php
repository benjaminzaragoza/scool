<?php

namespace Tests\Feature\Web\Curriculum;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class CurriculumSubjectsControllerTest.
 *
 * @package Tests\Feature
 */
class CurriculumSubjectsControllerTest extends BaseTenantTest
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
    public function show_curriculum_subjects()
    {
        $subjects = initialize_fake_subjects();
        $this->loginAsSuperAdmin();
        $response = $this->get('curriculum/subjects');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.subjects.index');
        $response->assertViewHas('subjects', function ($returnedSubjects) use ($subjects) {
            return
                count($returnedSubjects) === 2 &&
                $returnedSubjects[0]['id'] === 1 &&
                $returnedSubjects[0]['name'] === "Disseny i implementació d’interfícies" &&
                $returnedSubjects[0]['shortname'] === 'Interfícies' &&
                $returnedSubjects[0]['code'] === 'DAM_MP7_UF1' &&
                $returnedSubjects[0]['number'] === 1 &&
                $returnedSubjects[0]['hours'] === 79 &&
                $returnedSubjects[0]['start'] === '2017-09-15' &&
                $returnedSubjects[0]['end'] === '2018-06-01' &&
                $returnedSubjects[0]['api_uri'] === 'subjects' &&
                $returnedSubjects[0]['created_at'] !== null &&
                $returnedSubjects[0]['created_at_timestamp'] !== null &&
                $returnedSubjects[0]['formatted_created_at'] !== null &&
                $returnedSubjects[0]['formatted_created_at_diff'] !== null &&
                $returnedSubjects[0]['updated_at_timestamp'] !== null &&
                $returnedSubjects[0]['formatted_updated_at'] !== null &&
                $returnedSubjects[0]['formatted_updated_at_diff'] !== null &&

                $returnedSubjects[0]['subject_group_id'] === 1 &&
                $returnedSubjects[0]['subject_group_name'] === 'Desenvolupament d’interfícies' &&
                $returnedSubjects[0]['subject_group_shortname'] === 'Interfícies' &&
                $returnedSubjects[0]['subject_group_code'] === 'DAM_MP7' &&
                $returnedSubjects[0]['subject_group_number'] === 7 &&
                $returnedSubjects[0]['subject_group_hours'] === 99 &&
                $returnedSubjects[0]['subject_group_free_hours'] === 0 &&
                $returnedSubjects[0]['subject_group_week_hours'] === 3 &&
                $returnedSubjects[0]['subject_group_start'] === '2017-09-15' &&
                $returnedSubjects[0]['subject_group_end'] === '2018-06-01' &&
                $returnedSubjects[0]['subject_group_type'] === 'Normal' &&
                $returnedSubjects[0]['study_id'] === 1 &&
                $returnedSubjects[0]['study_name'] === 'Desenvolupament Aplicacions Multiplataforma' &&
                $returnedSubjects[0]['study_shortname'] === 'Des. Apps Multiplataforma' &&
                $returnedSubjects[0]['study_code'] === 'DAM' &&
                $returnedSubjects[0]['course_id'] === 1 &&
                $returnedSubjects[0]['course_name'] === 'Segon Curs Desenvolupament Aplicacions Multiplataforma' &&
                $returnedSubjects[0]['course_code'] === '2DAM' &&
                $returnedSubjects[0]['course_order'] === 2 &&
                $returnedSubjects[0]['type_id'] === 1;
        });
        $response->assertViewHas('studies', function ($returnedStudies) {
            return
                count($returnedStudies) === 1 &&
                $returnedStudies[0]['id'] === 1 &&
                $returnedStudies[0]['name'] === 'Desenvolupament Aplicacions Multiplataforma' &&
                $returnedStudies[0]['shortname'] === 'Des. Apps Multiplataforma' &&
                $returnedStudies[0]['code'] === 'DAM' &&
                $returnedStudies[0]['api_uri'] === 'studies';
        });
        $response->assertViewHas('subject_groups', function ($returnedSubjectGroups) {
            return
                count($returnedSubjectGroups) === 1 &&
                $returnedSubjectGroups[0]['id'] === 1 &&
                $returnedSubjectGroups[0]['name'] === "Desenvolupament d’interfícies" &&
                $returnedSubjectGroups[0]['shortname'] === 'Interfícies' &&
                $returnedSubjectGroups[0]['code'] === 'DAM_MP7' &&
                $returnedSubjectGroups[0]['number'] === 7 &&
                $returnedSubjectGroups[0]['hours'] === 99 &&
                $returnedSubjectGroups[0]['free_hours'] === 99 &&
                $returnedSubjectGroups[0]['week_hours'] === 99 &&
                $returnedSubjectGroups[0]['start'] === '2017-09-15' &&
                $returnedSubjectGroups[0]['end'] === '2018-06-01' &&
                $returnedSubjectGroups[0]['type'] === 'Normal' &&
                $returnedSubjectGroups[0]['api_uri'] === 'subject_groups';
        });
        $response->assertViewHas('courses', function ($returnedCourses) {
            return
                count($returnedCourses) === 1 &&
                $returnedCourses[0]['id'] === 1 &&
                $returnedCourses[0]['name'] === 'Segon Curs Desenvolupament Aplicacions Multiplataforma' &&
                $returnedCourses[0]['code'] === '2DAM' &&
                $returnedCourses[0]['order'] === 2 &&
                $returnedCourses[0]['api_uri'] === 'courses';
        });
        $response->assertViewHas('departments', function ($returnedDepartments) {
            dump($returnedDepartments);
            return
                count($returnedDepartments) === 1 &&
                $returnedDepartments[0]['id'] === 1 &&
                $returnedDepartments[0]['name'] === 'Segon Curs Desenvolupament Aplicacions Multiplataforma' &&
                $returnedDepartments[0]['shortname'] === 'Segon Curs Desenvolupament Aplicacions Multiplataforma' &&
                $returnedDepartments[0]['code'] === '2DAM' &&
                $returnedDepartments[0]['order'] === 2 &&
                $returnedDepartments[0]['api_uri'] === 'deparments';
        });
        $response->assertViewHas('families', function ($returnedFamilies) {
            return
                count($returnedFamilies) === 1 &&
                $returnedFamilies[0]['id'] === 1 &&
                $returnedFamilies[0]['name'] === 'Segon Curs Desenvolupament Aplicacions Multiplataforma' &&
                $returnedFamilies[0]['code'] === '2DAM' &&
                $returnedFamilies[0]['order'] === 2 &&
                $returnedFamilies[0]['api_uri'] === 'courses';
        });
    }

    /** @test */
    public function regular_user_cannot_show_curriculum_module()
    {
        $this->login();
        $response = $this->get('/curriculum');
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_show_curriculum_module()
    {
        $response = $this->get('/curriculum');
        $response->assertRedirect('login');
    }

}
