<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Subjects;

use App\Events\Subjects\SubjectNameUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class SubjectsNameControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class SubjectsNameControllerTest extends BaseTenantTest {

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
    public function can_update_subject_name()
    {

        $this->loginAsSuperAdmin('api');

        $subject = create_sample_subject();

        Event::fake();

        $response = $this->json('PUT','/api/v1/subjects/' . $subject->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(SubjectNameUpdated::class,function ($event) use ($subject){
            return $event->subject->is($subject);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOM',$result->name);
        $this->assertEquals($result->id,$subject->id);

        $subject = $subject->fresh();
        $this->assertEquals($subject->name,'NOUNOM');
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_update_subject_name()
    {
        $this->loginAsCurriculumManager('api');

        $subject = create_sample_subject();

        Event::fake();

        $response = $this->json('PUT','/api/v1/subjects/' . $subject->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(SubjectNameUpdated::class,function ($event) use ($subject){
            return $event->subject->is($subject);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOM',$result->name);
        $this->assertEquals($result->id,$subject->id);

        $subject = $subject->fresh();
        $this->assertEquals($subject->name,'NOUNOM');
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_subject_name()
    {
        $this->login('api');
        $subject = create_sample_subject();
        $response = $this->json('PUT','/api/v1/subjects/' . $subject->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_update_subject_name()
    {
        $subject = create_sample_subject();
        $response = $this->json('PUT','/api/v1/subjects/' . $subject->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertStatus(401);
    }
}
