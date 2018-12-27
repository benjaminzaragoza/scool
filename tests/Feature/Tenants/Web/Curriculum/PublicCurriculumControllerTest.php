<?php

namespace Tests\Feature\Web\Curriculum;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PublicCurriculumControllerTest.
 *
 * @package Tests\Feature
 */
class PublicCurriculumControllerTest extends BaseTenantTest
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
    public function show_public_curriculum_module()
    {
        $studies = create_sample_studies();
        $response = $this->get('/public/curriculum');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.public.index');
        $response->assertViewHas('families', function ($returnedFamilies) {
            return
                count($returnedFamilies) === 2 &&
                $returnedFamilies[0]['id'] === 1 &&
                $returnedFamilies[0]['name'] === 'Informàtica' &&
                $returnedFamilies[0]['code'] === 'INF';
        });
    }
}
