<?php

namespace Tests\Feature;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class LessonControllerTest.
 *
 * @package Tests\Feature
 */
class LessonControllerTest extends BaseTenantTest
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
    public function can_see_subject_lessons_management()
    {
        initialize_families();
        initialize_subjects();
        initialize_fake_lessons();
        $manager = create(User::class);
        $this->actingAs($manager);
        $role = Role::firstOrCreate(['name' => 'LessonsManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->get('/lessons');

        initialize_fake_lessons();

        $response->assertSuccessful();
        $response->assertViewIs('tenants.lessons.show');
        $response->assertViewHas('subjects');
        $response->assertViewHas('lessons');
        $response->assertViewHas('lessons',function ($lessons) {
            $lesson = $lessons->first();
            return check_lesson($lesson);
        });
    }

    /** @test */
    public function regular_user_cannot_see_subject_lessons_management()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('/lessons');

        $response->assertStatus(403);
    }
}
