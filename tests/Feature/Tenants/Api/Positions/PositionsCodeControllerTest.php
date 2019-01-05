<?php

namespace Tests\Feature\Tenants\Api\Positions;

use App\Events\Studies\StudyCodeUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class StudiesCodeControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class PositionsCodeControllerTest extends BaseTenantTest {

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
     * @group positions
     */
    public function can_update_study_code()
    {
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/code',[
            'code' => 'NOUCODIDAM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(StudyCodeUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUCODIDAM',$result->code);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->code,'NOUCODIDAM');
    }

    /**
     * @test
     * @group positions
     */
    public function manager_can_update_study_code()
    {
        $this->loginAsPositionsManager('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/code',[
            'code' => 'NOUCODIDAM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(StudyCodeUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUCODIDAM',$result->code);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->code,'NOUCODIDAM');
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_update_study_subject()
    {
        $this->login('api');
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/code',[
            'code' => 'NOUCODIDAM'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_update_study_subject()
    {
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/code',[
            'code' => 'NOUCODIDAM'
        ]);
        $response->assertStatus(401);
    }
}
