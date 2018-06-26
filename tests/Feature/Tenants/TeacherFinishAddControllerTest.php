<?php

namespace Tests\Feature\Tenants;

use App\Mail\TeacherWelcome;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Mail;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherFinishAddControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class TeacherFinishAddControllerTest extends BaseTenantTest
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
    public function finish_adding_teacher_with_welcome_email()
    {
        $staffManager = create(User::class);
        $this->actingAs($staffManager,'api');
        $role = Role::firstOrCreate(['name' => 'TeachersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        Mail::fake();

        $user = create(User::class);

        $response = $this->json('POST','/api/v1/teacher/finish_add', [
            'user_id' => $user->id,
            'welcome_email' => true
        ]);

        $response->assertSuccessful();

        Mail::assertQueued(TeacherWelcome::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /** @test */
    public function finish_adding_teacher_with_welcome_email_404_for_unexisting_user()
    {
        $staffManager = create(User::class);
        $this->actingAs($staffManager,'api');
        $role = Role::firstOrCreate(['name' => 'TeachersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        $response = $this->json('POST','/api/v1/teacher/finish_add', [
            'user_id' => 9999999999,
            'welcome_email' => true
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function finish_adding_teacher_with_welcome_email_validation()
    {
        $staffManager = create(User::class);
        $this->actingAs($staffManager,'api');
        $role = Role::firstOrCreate(['name' => 'TeachersManager','guard_name' => 'web']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        $response = $this->json('POST','/api/v1/teacher/finish_add', []);

        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_cannot_finish_adding_teacher_with_welcome_email()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/teacher/finish_add', [
            'user_id' => 1
        ]);
        $response->assertStatus(403);
    }

}
