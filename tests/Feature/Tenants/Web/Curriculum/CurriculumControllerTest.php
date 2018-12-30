<?php

namespace Tests\Feature\Web\Curriculum;

use App\Models\Course;
use App\Models\SubjectGroup;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class CurriculumControllerTest.
 *
 * @package Tests\Feature
 */
class CurriculumControllerTest extends BaseTenantTest
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
    public function show_curriculum_module()
    {
        $studies = create_sample_studies();
        SubjectGroup::firstOrCreate([
            'name' => 'Desenvolupament d’interfícies',
            'shortname' => 'Interfícies',
            'description' => 'Bla bla bla',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $studies[0]->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01',
            'subjects_number' => 3
        ]);
        Course::firstOrCreate([
            'code' => '2DAM',
            'name' => 'Segon Curs Desenvolupament Aplicacions Multiplataforma',
            'order' => 2,
            'study_id' => $studies[0]->id
        ]);
        $this->loginAsSuperAdmin();
        $response = $this->get('/curriculum');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.index');
        $response->assertViewHas('studies', function ($returnedStudies) use ($studies) {
            return
                count($returnedStudies) === 4 &&
                $returnedStudies[0]['name'] === 'Desenvolupament Aplicacions Multiplataforma' &&
                $returnedStudies[0]['shortname'] === 'Des. Apps Multiplataforma' &&
                $returnedStudies[0]['code'] === "DAM" &&
                $returnedStudies[0]['created_at'] !== null &&
                $returnedStudies[0]['created_at_timestamp'] !== null &&
                $returnedStudies[0]['formatted_created_at'] !== null &&
                $returnedStudies[0]['formatted_created_at_diff'] !== null &&
                $returnedStudies[0]['updated_at_timestamp'] !== null &&
                $returnedStudies[0]['formatted_updated_at'] !== null &&
                $returnedStudies[0]['formatted_updated_at_diff'] !== null;
        });
        $response->assertViewHas('departments', function ($returnedDepartments) {
            return
                count($returnedDepartments) === 2 &&
                $returnedDepartments[0]['id'] === 1 &&
                $returnedDepartments[0]['name'] === 'Departament Informàtica' &&
                $returnedDepartments[0]['shortname'] === 'Informàtica' &&
                $returnedDepartments[0]['code'] === 'INFORMÀTICA' &&
                $returnedDepartments[0]['order'] === 1;
        });
        $response->assertViewHas('families', function ($returnedFamilies) {
            return
                count($returnedFamilies) === 2 &&
                $returnedFamilies[0]['id'] === 1 &&
                $returnedFamilies[0]['name'] === 'Informàtica' &&
                $returnedFamilies[0]['code'] === 'INF';
        });
        $response->assertViewHas('tags', function ($returnedTags) {
            return
                count($returnedTags) === 2 &&
                $returnedTags[0]['id'] === 1 &&
                $returnedTags[0]['value'] === 'LOE' &&
                $returnedTags[0]['description'] === 'Ley Orgànica de Educación';
        });
        $response->assertViewHas('subjectGroups', function ($returnedSubjectGroups) {
            return
                count($returnedSubjectGroups) === 1 &&
                $returnedSubjectGroups[0]['id'] === 1 &&
                $returnedSubjectGroups[0]['name'] === "Desenvolupament d’interfícies" &&
                $returnedSubjectGroups[0]['shortname'] === "Interfícies" &&
                $returnedSubjectGroups[0]['description'] === 'Bla bla bla' &&
                $returnedSubjectGroups[0]['code'] === 'DAM_MP7' &&
                $returnedSubjectGroups[0]['number'] === 7 &&
                $returnedSubjectGroups[0]['hours'] === 99 &&
                $returnedSubjectGroups[0]['free_hours'] === 99 &&
                $returnedSubjectGroups[0]['week_hours'] === 99 &&
                $returnedSubjectGroups[0]['start'] === '2017-09-15' &&
                $returnedSubjectGroups[0]['end'] === '2018-06-01' &&
                $returnedSubjectGroups[0]['api_uri'] === 'subject_groups' &&
                $returnedSubjectGroups[0]['created_at'] !== null &&
                $returnedSubjectGroups[0]['created_at_timestamp'] !== null &&
                $returnedSubjectGroups[0]['formatted_created_at'] !== null &&
                $returnedSubjectGroups[0]['formatted_created_at_diff'] !== null &&
                $returnedSubjectGroups[0]['updated_at_timestamp'] !== null &&
                $returnedSubjectGroups[0]['formatted_updated_at'] !== null &&
                $returnedSubjectGroups[0]['formatted_updated_at_diff'] !== null &&

                $returnedSubjectGroups[0]['study_id'] === 1 &&
                $returnedSubjectGroups[0]['study_name'] === 'Desenvolupament Aplicacions Multiplataforma' &&
                $returnedSubjectGroups[0]['study_shortname'] === 'Des. Apps Multiplataforma' &&
                $returnedSubjectGroups[0]['study_code'] === 'DAM';
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
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_show_curriculum_module()
    {
        $this->login();
        $response = $this->get('/curriculum');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_show_curriculum_module()
    {
        $response = $this->get('/curriculum');
        $response->assertRedirect('login');
    }

}
