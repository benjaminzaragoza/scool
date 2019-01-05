<?php

namespace Tests\Feature\Tenants\Api\Positions;

use App\Events\Studies\StudyNameUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class StudiesNameControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class PositionsNameControllerTest extends BaseTenantTest {

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
    public function can_update_study_name()
    {
        $this->loginAsSuperAdmin('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(StudyNameUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOM',$result->name);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->name,'NOUNOM');
    }

    /**
     * @test
     * @group positions
     */
    public function manager_can_update_study_name()
    {
        $this->loginAsPositionsManager('api');

        $study = create_sample_study();

        Event::fake();

        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(StudyNameUpdated::class,function ($event) use ($study){
            return $event->study->is($study);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOM',$result->name);
        $this->assertEquals($result->id,$study->id);

        $study = $study->fresh();
        $this->assertEquals($study->name,'NOUNOM');
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_update_study_name()
    {
        $this->login('api');
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_update_study_name()
    {
        $study = create_sample_study();
        $response = $this->json('PUT','/api/v1/studies/' . $study->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertStatus(401);
    }
}
