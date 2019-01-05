<?php

namespace Tests\Feature\Tenants\Api\Curriculum\Studies;

use App\Events\Studies\PositionShortnameUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class StudiesShortnameControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class StudiesShortnameControllerTest extends BaseTenantTest {

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
    public function can_update_study_shortname()
    {
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/shortname',[
            'shortname' => 'NOUNOMCURT'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(PositionShortnameUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOMCURT',$result->shortname);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->shortname,'NOUNOMCURT');
    }

    /**
     * @test
     * @group curriculum
     */
    public function manager_can_update_study_shortname()
    {
        $this->loginAsCurriculumManager('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/shortname',[
            'shortname' => 'NOUNOMCURT'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(PositionShortnameUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOMCURT',$result->shortname);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->shortname,'NOUNOMCURT');
    }

    /**
     * @test
     * @group curriculum
     */
    public function regular_user_cannot_update_study_shortname()
    {
        $this->login('api');
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/shortname',[
            'name' => 'NOUNOMCURT'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group curriculum
     */
    public function guest_user_cannot_update_study_shortname()
    {
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/shortname',[
            'name' => 'NOUNOMCURT'
        ]);
        $response->assertStatus(401);
    }
}
