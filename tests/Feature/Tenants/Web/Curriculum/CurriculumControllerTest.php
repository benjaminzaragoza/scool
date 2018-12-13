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

    /** @test */
    public function show_curriculum_module()
    {
        $studies = create_sample_studies();
        $this->loginAsSuperAdmin();
        $response = $this->get('/curriculum');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.index');
        $response->assertViewHas('studies', function ($returnedStudies) use ($studies) {
            dd($returnedStudies);
//            return
//                $returnedLogs[0]['user_name']=== $logs[0]['user']->name &&
//                $returnedLogs[0]['color'] === 'teal' &&
//                $returnedLogs[0]['action_type'] === 'update' &&
//                $returnedLogs[0]['text'] === "Ha creat la incidència TODO_LINK_INCIDENCIA" &&
//                $returnedLogs[0]['icon'] === 'home' &&
//                $returnedLogs[1]['text'] === "Ha modificat la incidència TODO_LINK_INCIDENCIA" &&
//                $returnedLogs[1]['action_type'] === 'update' &&
//                $returnedLogs[2]['text'] === "Ha modificat la incidència TODO_LINK_INCIDENCIA" &&
//                $returnedLogs[2]['action_type'] === 'update' &&
//                $returnedLogs[3]['text'] === "BLA BLA BLA";
        });

//        $logs = sample_logs();
//        $user = factory(User::class)->create();
//        $role = Role::firstOrCreate(['name' => 'ChangelogManager']);
//        Config::set('auth.providers.users.model', User::class);
//        $user->assignRole($role);
//        $this->actingAs($user);
//        $response = $this->get('/changelog');
//        $response->assertSuccessful();
//        $response->assertViewIs('tenants.changelog.index');
//        $response->assertViewHas('logs', function ($returnedLogs) use ($logs) {
//            return
//                $returnedLogs[0]['user_name']=== $logs[0]['user']->name &&
//                $returnedLogs[0]['color'] === 'teal' &&
//                $returnedLogs[0]['action_type'] === 'update' &&
//                $returnedLogs[0]['text'] === "Ha creat la incidència TODO_LINK_INCIDENCIA" &&
//                $returnedLogs[0]['icon'] === 'home' &&
//                $returnedLogs[1]['text'] === "Ha modificat la incidència TODO_LINK_INCIDENCIA" &&
//                $returnedLogs[1]['action_type'] === 'update' &&
//                $returnedLogs[2]['text'] === "Ha modificat la incidència TODO_LINK_INCIDENCIA" &&
//                $returnedLogs[2]['action_type'] === 'update' &&
//                $returnedLogs[3]['text'] === "BLA BLA BLA";
//        });
//        $response->assertViewHas('users');
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
