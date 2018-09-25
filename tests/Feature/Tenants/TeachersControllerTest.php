<?php

namespace Tests\Feature\Tenants;

use App\Models\Teacher;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
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
        $response->assertViewHas('users');
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

    /** @test */
    public function add_teacher()
    {
        $teachersManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $teachersManager->assignRole($role);

        $this->actingAs($teachersManager,'api');

        $user = create(User::class);

        $response = $this->json('POST','/api/v1/teachers',[
            'user_id' => $user->id,
            'code' => '001',
            'department_id' => 1,
            'administrative_status_id' => 1,
            'specialty_id' => 1,
        ]);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals('2', $result->user_id);
        $this->assertEquals('001', $result->code);
        $this->assertEquals(1, $result->department_id);
        $this->assertEquals(1, $result->administrative_status_id);
        $this->assertEquals(1, $result->specialty_id);

        $this->assertNotNull($teacher = Teacher::first());
        $this->assertEquals('2', $teacher->user_id);
        $this->assertEquals('001', $teacher->code);
        $this->assertEquals(1, $teacher->department_id);
        $this->assertEquals(1, $teacher->administrative_status_id);
        $this->assertEquals(1, $teacher->specialty_id);

    }

    /** @test */
    public function add_teachers_validation()
    {
        $teachersManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $teachersManager->assignRole($role);

        $this->actingAs($teachersManager,'api');

        $response = $this->json('POST','/api/v1/teachers',[]);

        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_cannot_add_teacher()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');


        $response = $this->json('POST','/api/v1/teachers');

        $response->assertStatus(403);
    }

    /** @test */
    public function remove_teacher()
    {
        $teachersManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $teachersManager->assignRole($role);

        $this->actingAs($teachersManager,'api');

        $teacher = Teacher::create([
            'user_id' => 1,
            'code' => '001',
            'department_id' => 1,
            'administrative_status_id' => 1,
            'specialty_id' => 1,
        ]);

        $response = $this->json('DELETE','/api/v1/teachers/' . $teacher->id);

        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $result = json_decode($response->getContent());
        $this->assertEquals('1', $result->user_id);
        $this->assertEquals('001', $result->code);
        $this->assertEquals(1, $result->department_id);
        $this->assertEquals(1, $result->administrative_status_id);
        $this->assertEquals(1, $result->specialty_id);
        $this->assertNull(Teacher::find($teacher->id));
    }

    /** @test */
    public function regular_user_cannot_remove_teacher()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $teacher = Teacher::create([
            'user_id' => 1,
            'code' => '001',
            'department_id' => 1,
            'administrative_status_id' => 1,
            'specialty_id' => 1,
        ]);

        $response = $this->json('DELETE','/api/v1/teachers/' . $teacher->id);

        $response->assertStatus(403);
    }

}
