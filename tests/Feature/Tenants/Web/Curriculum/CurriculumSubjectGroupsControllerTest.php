<?php

namespace Tests\Feature\Web\Curriculum;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class CurriculumSubjectGroupsControllerTest.
 *
 * @package Tests\Feature
 */
class CurriculumSubjectGroupsControllerTest extends BaseTenantTest
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
    public function show_curriculum_subject_groups()
    {
        $subjectGroups = initialize_fake_subjectGroups();
        $this->loginAsSuperAdmin();
        $response = $this->get('/curriculum/subjectGroups');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.subjectGroups.index');
        $response->assertViewHas('subjectGroups', function ($returnedSubjectGroups) use ($subjectGroups) {
            return
                count($returnedSubjectGroups) === 3 &&
                $returnedSubjectGroups[0]['id'] === 1 &&
                $returnedSubjectGroups[0]['name'] === "Desenvolupament d’interfícies" &&
                $returnedSubjectGroups[0]['shortname'] === "Interfícies" &&
                $returnedSubjectGroups[0]['description'] === null &&
                $returnedSubjectGroups[0]['code'] === 'DAM_MP7' &&
                $returnedSubjectGroups[0]['number'] === 7 &&
                $returnedSubjectGroups[0]['hours'] === 99 &&
                $returnedSubjectGroups[0]['free_hours'] === 99 &&
                $returnedSubjectGroups[0]['week_hours'] === 99 &&
                $returnedSubjectGroups[0]['start'] === '2017-09-15' &&
                $returnedSubjectGroups[0]['end'] === '2018-06-01' &&
                $returnedSubjectGroups[0]['type'] === 'Normal' &&
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

    }

    /**
     * @test
     * @group curriculum
     */
    public function curriculum_manager_curriculum_subject_groups()
    {
        $subjectGroups = initialize_fake_subjectGroups();
        $this->loginAsCurriculumManager();
        $response = $this->get('/curriculum/subjectGroups');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.subjectGroups.index');
        $response->assertViewHas('subjectGroups', function ($returnedSubjectGroups) use ($subjectGroups) {
            return
                count($returnedSubjectGroups) === 3 &&
                $returnedSubjectGroups[0]['id'] === 1 &&
                $returnedSubjectGroups[0]['name'] === "Desenvolupament d’interfícies" &&
                $returnedSubjectGroups[0]['shortname'] === "Interfícies" &&
                $returnedSubjectGroups[0]['description'] === null &&
                $returnedSubjectGroups[0]['code'] === 'DAM_MP7' &&
                $returnedSubjectGroups[0]['number'] === 7 &&
                $returnedSubjectGroups[0]['hours'] === 99 &&
                $returnedSubjectGroups[0]['free_hours'] === 99 &&
                $returnedSubjectGroups[0]['week_hours'] === 99 &&
                $returnedSubjectGroups[0]['start'] === '2017-09-15' &&
                $returnedSubjectGroups[0]['end'] === '2018-06-01' &&
                $returnedSubjectGroups[0]['type'] === 'Normal' &&
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

    }

    /** @test */
    public function regular_user_cannot_show_curriculum_module()
    {
        $this->login();
        $response = $this->get('/curriculum/subjectGroups');
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_show_curriculum_module()
    {
        $response = $this->get('/curriculum/subjectGroups');
        $response->assertRedirect('login');
    }

}
