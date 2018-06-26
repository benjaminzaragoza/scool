<?php

namespace Tests\Feature;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ApprovedTeacherControllerTest.
 *
 * @package Tests\Feature
 */
class ApprovedTeacherControllerTest extends BaseTenantTest
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
    public function show_personal_data_management()
    {
        $this->withoutExceptionHandling();

//        initialize_tenant_roles_and_permissions();
//        initialize_user_types();
//        initialize_job_types();
//        initialize_forces();
//        initialize_departments();
//        initialize_families();
//        initialize_specialities();
//        initialize_users();
//        initialize_teachers();

        $usersManager = create(User::class);
        $this->actingAs($usersManager);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->get('/personal_data');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.people.show');
        $response->assertViewHas('people');
    }

    /** @test */
    public function regular_user_cannot_show_personal_data_management()
    {

        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('/personal_data');

        $response->assertStatus(403);
    }

}
