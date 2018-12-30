<?php

namespace Tests\Feature\Tenants\Api\Curriculum\SubjectGroups;

use App\Events\SubjectGroups\SubjectGroupNameUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class SubjectGroupsNameControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class SubjectGroupsNameControllerTest extends BaseTenantTest {

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
    public function can_update_subjectGroup_name()
    {
        $this->loginAsSuperAdmin('api');

        $subjectGroup = create_sample_subject_group();

        Event::fake();

        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(SubjectGroupNameUpdated::class,function ($event) use ($subjectGroup){
            return $event->subjectGroup->is($subjectGroup);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOM',$result->name);
        $this->assertEquals($result->id,$subjectGroup->id);

        $subjectGroup = $subjectGroup->fresh();
        $this->assertEquals($subjectGroup->name,'NOUNOM');
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_update_subjectGroup_name()
    {
        $this->loginAsCurriculumManager('api');

        $subjectGroup = create_sample_subject_group();

        Event::fake();

        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(SubjectGroupNameUpdated::class,function ($event) use ($subjectGroup){
            return $event->subjectGroup->is($subjectGroup);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOM',$result->name);
        $this->assertEquals($result->id,$subjectGroup->id);

        $subjectGroup = $subjectGroup->fresh();
        $this->assertEquals($subjectGroup->name,'NOUNOM');
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_subjectGroup_name()
    {
        $this->login('api');
        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_update_subjectGroup_name()
    {
        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertStatus(401);
    }
}
