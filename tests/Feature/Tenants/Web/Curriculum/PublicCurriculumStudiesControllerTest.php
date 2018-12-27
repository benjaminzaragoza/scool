<?php

namespace Tests\Feature\Web\Curriculum;

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
        $this->withoutExceptionHandling();
        $study = create_sample_study();

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
                $returnedStudy['formatted_updated_at_diff'] !== null;
        });
    }
}
