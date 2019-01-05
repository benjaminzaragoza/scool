<?php

namespace Tests\Feature\Tenants\Api\Positions;

use App\Events\Positions\PositionStored;
use App\Models\Position;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PositionsControllerTest.
 *
 * @package Tests\Feature
 */
class PositionsControllerTest extends BaseTenantTest
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
     * @group positions
     */
    public function can_list_positions()
    {
        initialize_positions();
        $this->loginAsSuperAdmin('api');

        $response =  $this->json('GET','/api/v1/positions');
        $response->assertSuccessful();
        $positions = json_decode($response->getContent());
        $this->assertCount(2,$positions);
        $this->assertSame(1,$positions[0]->id);
        $this->assertEquals('Coordinador TIC/TAC',$positions[0]->name);
        $this->assertEquals('Coord. TIC',$positions[0]->shortname);
        $this->assertEquals('TICTAC',$positions[0]->code);
        $this->assertNotNull($positions[0]->created_at);
        $this->assertNotNull($positions[0]->created_at_timestamp);
        $this->assertNotNull($positions[0]->formatted_created_at);
        $this->assertNotNull($positions[0]->formatted_created_at_diff);
        $this->assertNotNull($positions[0]->updated_at);
        $this->assertNotNull($positions[0]->updated_at_timestamp);
        $this->assertNotNull($positions[0]->formatted_updated_at);
        $this->assertNotNull($positions[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group positions
     */
    public function positions_manager_can_list_positions()
    {
        initialize_positions();
        $this->loginAsPositionsManager('api');

        $response =  $this->json('GET','/api/v1/positions');
        $response->assertSuccessful();
        $positions = json_decode($response->getContent());
        $this->assertCount(2,$positions);
        $this->assertSame(1,$positions[0]->id);
        $this->assertEquals('Coordinador TIC/TAC',$positions[0]->name);
        $this->assertEquals('Coord. TIC',$positions[0]->shortname);
        $this->assertEquals('TICTAC',$positions[0]->code);
        $this->assertNotNull($positions[0]->created_at);
        $this->assertNotNull($positions[0]->created_at_timestamp);
        $this->assertNotNull($positions[0]->formatted_created_at);
        $this->assertNotNull($positions[0]->formatted_created_at_diff);
        $this->assertNotNull($positions[0]->updated_at);
        $this->assertNotNull($positions[0]->updated_at_timestamp);
        $this->assertNotNull($positions[0]->formatted_updated_at);
        $this->assertNotNull($positions[0]->formatted_updated_at_diff);
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_list_positions()
    {
        $this->login('api');
        $response = $this->json('GET','/api/v1/positions');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_list_positions()
    {
        $response = $this->json('GET','/api/v1/positions');
        $response->assertStatus(401);
    }


    /**
     * @test
     * @group positions
     */
    public function can_store_positions()
    {
        $this->loginAsSuperAdmin('api');

        Event::fake();

        $response =  $this->json('POST','/api/v1/positions',$position = [
            'name' => 'Director',
            'shortname' => 'Director',
            'code' => 'DIRE',
        ]);
        $response->assertSuccessful();
        $createdPosition = json_decode($response->getContent());
        Event::assertDispatched(PositionStored::class,function ($event) use ($createdPosition){
            return $event->position->is(Position::findOrFail($createdPosition->id));
        });
        $this->assertSame($createdPosition->id,1);
        $this->assertEquals($createdPosition->name,'Director');
        $this->assertEquals($createdPosition->shortname,'Director');
        $this->assertEquals($createdPosition->code,'DIRE');

        $this->assertNotNull($createdPosition->created_at);
        $this->assertNotNull($createdPosition->created_at_timestamp);
        $this->assertNotNull($createdPosition->formatted_created_at);
        $this->assertNotNull($createdPosition->formatted_created_at_diff);
        $this->assertNotNull($createdPosition->updated_at);
        $this->assertNotNull($createdPosition->updated_at_timestamp);
        $this->assertNotNull($createdPosition->formatted_updated_at);
        $this->assertNotNull($createdPosition->formatted_updated_at_diff);

        try {
            $position = Position::findOrFail($createdPosition->id);
        } catch (\Exception $e) {
            $this->fails('Position not found at database!');
        }

        $this->assertSame($position->id,1);
        $this->assertEquals($position->name,'Director');
        $this->assertEquals($position->shortname,'Director');
        $this->assertEquals($position->code,'DIRE');
        $this->assertNotNull($position->created_at);
        $this->assertNotNull($position->created_at_timestamp);
        $this->assertNotNull($position->formatted_created_at);
        $this->assertNotNull($position->formatted_created_at_diff);
        $this->assertNotNull($position->updated_at);
        $this->assertNotNull($position->updated_at_timestamp);
        $this->assertNotNull($position->formatted_updated_at);
        $this->assertNotNull($position->formatted_updated_at_diff);

    }

    /**
     * @test
     * @group positions
     */
    public function positions_manager_can_store_positions()
    {
        $this->loginAsPositionsManager('api');

        Event::fake();

        $response =  $this->json('POST','/api/v1/positions',$position = [
            'name' => 'Director',
            'shortname' => 'Director',
            'code' => 'DIRE',
        ]);
        $response->assertSuccessful();
        $createdPosition = json_decode($response->getContent());
        Event::assertDispatched(PositionStored::class,function ($event) use ($createdPosition){
            return $event->position->is(Position::findOrFail($createdPosition->id));
        });
        $this->assertSame($createdPosition->id,1);
        $this->assertEquals($createdPosition->name,'Director');
        $this->assertEquals($createdPosition->shortname,'Director');
        $this->assertEquals($createdPosition->code,'DIRE');

        $this->assertNotNull($createdPosition->created_at);
        $this->assertNotNull($createdPosition->created_at_timestamp);
        $this->assertNotNull($createdPosition->formatted_created_at);
        $this->assertNotNull($createdPosition->formatted_created_at_diff);
        $this->assertNotNull($createdPosition->updated_at);
        $this->assertNotNull($createdPosition->updated_at_timestamp);
        $this->assertNotNull($createdPosition->formatted_updated_at);
        $this->assertNotNull($createdPosition->formatted_updated_at_diff);

        try {
            $position = Position::findOrFail($createdPosition->id);
        } catch (\Exception $e) {
            $this->fails('Position not found at database!');
        }

        $this->assertSame($position->id,1);
        $this->assertEquals($position->name,'Director');
        $this->assertEquals($position->shortname,'Director');
        $this->assertEquals($position->code,'DIRE');
        $this->assertNotNull($position->created_at);
        $this->assertNotNull($position->created_at_timestamp);
        $this->assertNotNull($position->formatted_created_at);
        $this->assertNotNull($position->formatted_created_at_diff);
        $this->assertNotNull($position->updated_at);
        $this->assertNotNull($position->updated_at_timestamp);
        $this->assertNotNull($position->formatted_updated_at);
        $this->assertNotNull($position->formatted_updated_at_diff);

    }

    /**
     * @test
     * @group positions
     */
    public function can_store_positions_validation()
    {
        $this->loginAsSuperAdmin('api');
        $response = $this->json('POST', '/api/v1/positions', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group positions
     */
    public function can_delete_positions()
    {
        $this->loginAsSuperAdmin('api');

        $position = create_sample_position();

        $response = $this->json('DELETE','/api/v1/positions/' . $position->id);

        $response->assertSuccessful();
        $position = $position->fresh();
        $this->assertNull($position);
    }

    /**
     * @test
     * @group positions
     */
    public function positions_manager_can_delete_positions()
    {
        $this->loginAsPositionsManager('api');

        $position = create_sample_position();

        $response = $this->json('DELETE','/api/v1/positions/' . $position->id);

        $response->assertSuccessful();
        $position = $position->fresh();
        $this->assertNull($position);
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_delete_positions()
    {
        $this->login('api');

        $position = create_sample_position();

        $response = $this->json('DELETE','/api/v1/positions/' . $position->id);

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_delete_positions()
    {
       $position = create_sample_position();

        $response = $this->json('DELETE','/api/v1/positions/' . $position->id);

        $response->assertStatus(401);
    }
}
