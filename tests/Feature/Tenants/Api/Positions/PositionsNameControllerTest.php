<?php

namespace Tests\Feature\Tenants\Api\Positions;

use App\Events\Positions\PositionNameUpdated;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PositionsNameControllerTest.
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
    public function can_update_position_name()
    {
        $this->loginAsSuperAdmin('api');

        $position = create_sample_position();

        Event::fake();

        $response = $this->json('PUT','/api/v1/positions/' . $position->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(PositionNameUpdated::class,function ($event) use ($position){
            return $event->position->is($position);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOM',$result->name);
        $this->assertEquals($result->id,$position->id);

        $position = $position->fresh();
        $this->assertEquals($position->name,'NOUNOM');
    }

    /**
     * @test
     * @group positions
     */
    public function manager_can_update_position_name()
    {
        $this->loginAsPositionsManager('api');

        $position = create_sample_position();

        Event::fake();

        $response = $this->json('PUT','/api/v1/positions/' . $position->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(PositionNameUpdated::class,function ($event) use ($position){
            return $event->position->is($position);
        });
        $result = json_decode($response->getContent());
        $this->assertEquals('NOUNOM',$result->name);
        $this->assertEquals($result->id,$position->id);

        $position = $position->fresh();
        $this->assertEquals($position->name,'NOUNOM');
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_update_position_name()
    {
        $this->login('api');
        $position = create_sample_position();
        $response = $this->json('PUT','/api/v1/positions/' . $position->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_update_position_name()
    {
        $position = create_sample_position();
        $response = $this->json('PUT','/api/v1/positions/' . $position->id . '/name',[
            'name' => 'NOUNOM'
        ]);
        $response->assertStatus(401);
    }
}
