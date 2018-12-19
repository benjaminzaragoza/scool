<?php

namespace Tests\Feature\Web\Curriculum;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class CurriculumSubjectsControllerTest.
 *
 * @package Tests\Feature
 */
class CurriculumSubjectsControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group curriculum
     */
    public function show_curriculum_subjects()
    {
        $this->withoutExceptionHandling();
        $subjects = initialize_fake_subjects();
        $this->loginAsSuperAdmin();
        $response = $this->get('curriculum/subjects');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.curriculum.subjects.index');
        $response->assertViewHas('subjects', function ($returnedSubjects) use ($subjects) {
            return
                count($returnedSubjects) === 4 &&
                $returnedSubjects[0]['name'] === 'Desenvolupament Aplicacions Multiplataforma' &&
                $returnedSubjects[0]['shortname'] === 'Des. Apps Multiplataforma' &&
                $returnedSubjects[0]['code'] === "DAM" &&
                $returnedSubjects[0]['created_at'] !== null &&
                $returnedSubjects[0]['created_at_timestamp'] !== null &&
                $returnedSubjects[0]['formatted_created_at'] !== null &&
                $returnedSubjects[0]['formatted_created_at_diff'] !== null &&
                $returnedSubjects[0]['updated_at_timestamp'] !== null &&
                $returnedSubjects[0]['formatted_updated_at'] !== null &&
                $returnedSubjects[0]['formatted_updated_at_diff'] !== null;
        });
//        $response->assertViewHas('departments', function ($returnedDepartments) {
//            return
//                count($returnedDepartments) === 2 &&
//                $returnedDepartments[0]['id'] === 1 &&
//                $returnedDepartments[0]['name'] === 'Departament Informàtica' &&
//                $returnedDepartments[0]['shortname'] === 'Informàtica' &&
//                $returnedDepartments[0]['code'] === 'INFORMÀTICA' &&
//                $returnedDepartments[0]['order'] === 1;
//        });
//        $response->assertViewHas('families', function ($returnedFamilies) {
//            return
//                count($returnedFamilies) === 2 &&
//                $returnedFamilies[0]['id'] === 1 &&
//                $returnedFamilies[0]['name'] === 'Informàtica' &&
//                $returnedFamilies[0]['code'] === 'INF';
//        });
//        $response->assertViewHas('tags', function ($returnedTags) {
//            return
//                count($returnedTags) === 2 &&
//                $returnedTags[0]['id'] === 1 &&
//                $returnedTags[0]['value'] === 'LOE' &&
//                $returnedTags[0]['description'] === 'Ley Orgànica de Educación';
//        });
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
