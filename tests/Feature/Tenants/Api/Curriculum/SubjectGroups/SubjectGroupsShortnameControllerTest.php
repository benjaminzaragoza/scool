<?php

namespace Tests\Feature\Tenants\Api\Curriculum\SubjectGroups;

use App\Events\SubjectGroups\SubjectGroupShortnameUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class SubjectGroupsShortnameControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class SubjectGroupsShortnameControllerTest extends BaseTenantTest {

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
    public function can_update_subjectGroup_shortname()
    {
        $this->loginAsSuperAdmin('api');

        $subjectGroup = create_sample_subject_group();

        Event::fake();

        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/shortname',[
            'shortname' => 'NOUNOMCURT'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(SubjectGroupShortnameUpdated::class,function ($event) use ($subjectGroup){
            return $event->subjectGroup->is($subjectGroup);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOMCURT',$result->shortname);
        $this->assertEquals($result->id,$subjectGroup->id);

        $subjectGroup = $subjectGroup->fresh();
        $this->assertEquals($subjectGroup->shortname,'NOUNOMCURT');
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_update_subjectGroup_shortname()
    {
        $this->loginAsCurriculumManager('api');

        $subjectGroup = create_sample_subject_group();

        Event::fake();

        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/shortname',[
            'shortname' => 'NOUNOMCURT'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(SubjectGroupShortnameUpdated::class,function ($event) use ($subjectGroup){
            return $event->subjectGroup->is($subjectGroup);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOMCURT',$result->shortname);
        $this->assertEquals($result->id,$subjectGroup->id);

        $subjectGroup = $subjectGroup->fresh();
        $this->assertEquals($subjectGroup->shortname,'NOUNOMCURT');
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_subjectGroup_shortname()
    {
        $this->login('api');
        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/shortname',[
            'shortname' => 'NOUNOMCURT'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_update_subjectGroup_shortname()
    {
        $subjectGroup = create_sample_subject_group();
        $response = $this->json('PUT','/api/v1/subject_groups/' . $subjectGroup->id . '/shortname',[
            'shortname' => 'NOUNOMCURT'
        ]);
        $response->assertStatus(401);
    }
}
