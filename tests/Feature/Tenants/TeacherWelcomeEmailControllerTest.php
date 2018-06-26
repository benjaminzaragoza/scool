<?php

namespace Tests\Feature\Tenants;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherWelcomeEmailControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class TeacherWelcomeEmailControllerTest extends BaseTenantTest
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
    public function show_teacher_welcome_email()
    {
        $this->withoutExceptionHandling();
        $teachersManager = create(User::class);
        $this->actingAs($teachersManager);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $teachersManager->assignRole($role);

        $response = $this->get('/mail/teacher_welcome');

        $response->assertSuccessful();
//        $response->assertViewIs('tenants.teachers.show');
//        $response->assertViewHas('pendingTeachers');
    }

    /** @test */
    public function regular_user_cannot_show_teacher_welcome_email()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('/mail/teacher_welcome');

        $response->assertStatus(403);
    }
}
