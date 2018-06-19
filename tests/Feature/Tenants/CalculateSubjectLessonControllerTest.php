<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\Subject;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CalculateSubjectLessonControllerTest.
 *
 * @package Tests\Feature
 */
class CalculateSubjectLessonControllerTest extends BaseTenantTest
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
    public function can_calculate_subject_lessons()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'LessonsManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        initialize_fake_subjects();
        initialize_fake_week_lessons();

        $this->assertCount(0,Lesson::all());


        $subject = Subject::findByCode('DAM_MP7_UF1');
        $response = $this->json('POST','/api/v1/lessons/subject/' . $subject->id .'/calculate');
        $response->assertSuccessful();

        $this->assertCount(50,Lesson::all());

    }
}
