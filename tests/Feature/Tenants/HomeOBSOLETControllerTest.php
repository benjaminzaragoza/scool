<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class HomeOBSOLETControllerTest.
 *
 * @package Tests\Feature
 */
class HomeOBSOLETControllerTest extends BaseTenantTest
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
    public function show_home()
    {
        $this->markTestSkipped('TODO');
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

        create_fake_audit_log_entries();

        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('/home');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.homeOBSOLET');
        $response->assertViewHas('auditLogItems',function ($entries) {
            $entry = $entries->first();
            return check_audit_log_entry($entry);
        });

        $response->assertViewHas('teacherTotals',function ($totals) {
            return $totals === '["Total","Reals"]';
        });

        $totalTeachers = Job::where('type_id',JobType::findByName('Professor/a')->id)->count();

        dump($totalTeachers);

        $response->assertViewHas('teacherTotalsData',function ($totals) use ($totalTeachers) {
            return json_decode($totals)[0] === $totalTeachers;
        });

//        $response->assertViewHas('$teacherTypes',function ($types) {
//            dd($types);
//        });

    }
}
