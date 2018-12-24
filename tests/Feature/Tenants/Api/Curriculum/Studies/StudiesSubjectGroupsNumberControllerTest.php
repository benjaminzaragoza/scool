<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Studies;

use App\Events\Studies\StudySubjectGroupsNumberUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class StudiesSubjectGroupsNumberControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class StudiesSubjectGroupsNumberControllerTest extends BaseTenantTest {

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
    public function can_update_study_SubjectGroupsNumber()
    {
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/subject_groups_number',[
            'subject_groups_number' => 13
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(StudySubjectGroupsNumberUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals(13,$result->subject_groups_number);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->subject_groups_number,13);
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_update_study_SubjectGroupsNumber()
    {

    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_study_SubjectGroupsNumber()
    {
        $this->login('api');
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/subject_groups_number',[
            'subject_groups_number' => 13
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_update_study_SubjectGroupsNumber()
    {
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/subject_groups_number',[
            'subject_groups_number' => 13
        ]);
        $response->assertStatus(401);
    }
}
