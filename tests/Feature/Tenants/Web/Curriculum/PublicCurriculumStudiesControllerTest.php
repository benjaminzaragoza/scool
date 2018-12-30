<?php

namespace Tests\Feature\Web\Curriculum;

use App\Models\Subject;
use App\Models\SubjectGroup;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PublicCurriculumStudiesControllerTest.
 *
 * @package Tests\Feature
 */
class PublicCurriculumStudiesControllerTest extends BaseTenantTest
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
    public function show_404_public_curriculum_studies_module_when_no_studies()
    {
        $response = $this->get('/public/curriculum/studies/' . 'nonexisting-slug-study');
        $response->assertStatus(404);
    }

    /**
     * @test
     * @group curriculum
     */
    public function show_public_curriculum_studies_module()
    {
        $study = create_sample_study();

        $group = SubjectGroup::firstOrCreate([
            'shortname' => 'Desenvolupament d’interfícies',
            'name' => 'Desenvolupament d’interfícies',
            'code' =>  'DAM_MP7',
            'number' => 7,
            'study_id' => $study->id,
            'hours' => 99,
            'free_hours' => 0, // Lliure disposició
            'week_hours' => 3,
            'start' => '2017-09-15',
            'end' => '2018-06-01',
        ]);

        Subject::firstOrCreate([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Disseny i implementació d’interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'subject_group_id' => $group->id,
            'study_id' => $study->id,
            'hours' => 79,
            'start' => '2017-09-15',
            'end' => '2018-06-01',
        ]);

        $response = $this->get('/public/curriculum/studies/' . $study->slug);
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.public.studies.show');
        $response->assertViewHas('study', function ($returnedStudy) {
            return
                $returnedStudy['name'] === 'Desenvolupament Aplicacions Multiplataforma' &&
                $returnedStudy['slug'] === 'desenvolupament-aplicacions-multiplataforma' &&
                $returnedStudy['shortname'] === 'Des. Aplicacions Multiplataforma' &&
                $returnedStudy['code'] === "DAM" &&
                $returnedStudy['created_at'] !== null &&
                $returnedStudy['created_at_timestamp'] !== null &&
                $returnedStudy['formatted_created_at'] !== null &&
                $returnedStudy['formatted_created_at_diff'] !== null &&
                $returnedStudy['updated_at_timestamp'] !== null &&
                $returnedStudy['formatted_updated_at'] !== null &&
                $returnedStudy['formatted_updated_at_diff'] !== null &&

                count($returnedStudy['subjectGroups']) === 1 &&
                $returnedStudy['subjectGroups'][0]['name'] == 'Desenvolupament d’interfícies' &&
                $returnedStudy['subjectGroups'][0]['shortname'] == 'Desenvolupament d’interfícies' &&
                $returnedStudy['subjectGroups'][0]['code'] == 'DAM_MP7' &&

                count($returnedStudy['subjectGroups'][0]['subjects']) === 1;
                $returnedStudy['subjectGroups'][0]['subjects'][0]['name'] == 'Disseny i implementació d’interfícies' &&
                $returnedStudy['subjectGroups'][0]['subjects'][0]['shortname'] == 'Disseny i implementació d’interfícies' &&
                $returnedStudy['subjectGroups'][0]['subjects'][0]['code'] == 'DAM_MP7_UF1';
        });
    }
}
