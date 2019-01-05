<?php

namespace Tests\Feature\Tenants\Api\Positions;

use App\Events\Positions\PositionCodeUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PositionsCodeControllerTest.
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
    public function can_update_position_code()
    {
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin('api');

        $position = create_sample_position();

        Event::fake();

        $response = $this->json('PUT','/api/v1/positions/' . $position->id . '/code',[
            'code' => 'NOUCODIDAM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(PositionCodeUpdated::class,function ($event) use ($position){
            return $event->position->is($position);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUCODIDAM',$result->code);
        $this->assertEquals($result->id,$position->id);

        $position = $position->fresh();
        $this->assertEquals($position->code,'NOUCODIDAM');
    }

    /**
     * @test
     * @group positions
     */
    public function manager_can_update_position_code()
    {
        $this->loginAsPositionsManager('api');

        $position = create_sample_position();

        Event::fake();

        $response = $this->json('PUT','/api/v1/positions/' . $position->id . '/code',[
            'code' => 'NOUCODIDAM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(PositionCodeUpdated::class,function ($event) use ($position){
            return $event->position->is($position);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUCODIDAM',$result->code);
        $this->assertEquals($result->id,$position->id);

        $position = $position->fresh();
        $this->assertEquals($position->code,'NOUCODIDAM');
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_update_position_subject()
    {
        $this->login('api');
        $position = create_sample_position();
        $response = $this->json('PUT','/api/v1/positions/' . $position->id . '/code',[
            'code' => 'NOUCODIDAM'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_update_position_subject()
    {
        $position = create_sample_position();
        $response = $this->json('PUT','/api/v1/positions/' . $position->id . '/code',[
            'code' => 'NOUCODIDAM'
        ]);
        $response->assertStatus(401);
    }
}
