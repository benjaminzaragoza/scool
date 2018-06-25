<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherAvailableCodeController.
 *
 * @package Tests\Feature\Tenants
 */
class TeacherAvailableCodeController extends BaseTenantTest
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
    public function show_next_teacher_available_code()
    {
        $teachersManager = create(User::class);
        $this->actingAs($teachersManager,'api');
        $role = Role::firstOrCreate(['name' => 'TeachersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $teachersManager->assignRole($role);

        $response = $this->json('GET','/api/v1/teacher/available_code');

        $response->assertSuccessful();
        $this->assertEquals('001',$response->getContent());
    }

    /** @test */
    public function regular_user_cannot_show_next_teacher_available_code()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/teacher/available_code');

        $response->assertStatus(403);
    }

}
