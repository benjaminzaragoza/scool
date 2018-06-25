<?php

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class JobsSheetControllerTest.
 *
 * @package Tests\Feature
 */
class JobsSheetControllerTest extends BaseTenantTest
{
    use RefreshDatabase;

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

    /** @test */
    public function can_see_jobs_sheet_for_active_users()
    {
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_departments();
        initialize_specialities();
        initialize_users();
        initialize_teachers();
        initialize_substitutes();

        $response = $this->get('/jobs/sheet_active_users');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.jobs.sheet');
        $response->assertViewHas('jobs');
        $response->assertViewHas('jobs',function ($jobs) {
            $job = $jobs->first();
            return check_sheet_job($job);
        });
    }

    /** @test */
    public function can_see_jobs_sheet_for_holders()
    {
        $this->withoutExceptionHandling();
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_departments();
        initialize_specialities();
        initialize_users();
        initialize_teachers();
        initialize_substitutes();

        $response = $this->get('/jobs/sheet_holders');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.jobs.sheet_holders');
        $response->assertViewHas('jobs');
        $response->assertViewHas('jobs',function ($jobs) {
            $job = $jobs->first();
            return check_sheet_job_for_holders($job);
        });
    }
}
