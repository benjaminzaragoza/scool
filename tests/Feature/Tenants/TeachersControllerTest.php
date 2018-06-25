<?php

namespace Tests\Feature\Tenants;

use App\Models\IdentifierType;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeachersControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class TeachersControllerTest extends BaseTenantTest
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
    public function show_teachers_management()
    {
        $this->withoutExceptionHandling();
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_departments();
        initialize_families();
        initialize_specialities();
        initialize_users();
        initialize_teachers();

        $staffManager = create(User::class);
        $this->actingAs($staffManager);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        $response = $this->get('/teachers');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.teachers.show');
        $response->assertViewHas('pendingTeachers');
        $response->assertViewHas('teachers');
        $response->assertViewHas('jobs');
        $response->assertViewHas('specialties');
        $response->assertViewHas('forces');
        $response->assertViewHas('administrativeStatuses');
        $response->assertViewHas('departments');
    }


    /** @test */
    public function show_teachers_management_check_teachers_data()
    {
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_departments();
        initialize_families();
        initialize_specialities();
        initialize_users();
        initialize_teachers();

        $staffManager = create(User::class);
        $this->actingAs($staffManager);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        $response = $this->get('/teachers');

        $response->assertSuccessful();

        // Check required Fields for teachers component
        $response->assertViewHas('teachers',function ($teachers) {
            $teacher = $teachers->first();
            return check_teacher($teacher);
        });
    }

    /** @test */
    public function list_teachers()
    {
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_departments();
        initialize_families();
        initialize_specialities();
        initialize_users();
        initialize_teachers();

        $teachersManager = create(User::class);
        $this->actingAs($teachersManager);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $teachersManager->assignRole($role);

        $this->actingAs($teachersManager,'api');

        $response = $this->json('GET','/api/v1/teachers');

        $response->assertSuccessful();
        $result= json_decode($response->getContent());
        $teacher = $result[0];
        $this->assertTrue(check_teacher($teacher));
    }

    /** @test */
    public function regular_users_cannot_list_teachers()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/teachers');

        $response->assertStatus(403);
    }

}
