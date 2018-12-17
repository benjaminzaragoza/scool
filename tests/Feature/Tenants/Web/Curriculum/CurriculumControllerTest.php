<?php

namespace Tests\Feature\Web\Curriculum;

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
        $this->loginAsSuperAdmin();
        $response = $this->get('/curriculum');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.index');
        $response->assertViewHas('studies', function ($returnedStudies) use ($studies) {
            return
                count($returnedStudies) === 4 &&
                $returnedStudies[0]['name'] === 'Desenvolupament Aplicacions Multiplataforma' &&
                $returnedStudies[0]['shortname'] === 'Des. Apps Multiplataforma' &&
                $returnedStudies[0]['code'] === "DAM";
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
