<?php

namespace Tests\Feature\Tenants\Api\Positions;

use App\Events\Positions\PositionStored;
use App\Events\Positions\PositionUserDeleted;
use App\Events\Positions\PositionUserStored;
use App\Models\Position;
use App\Models\User;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PositionUsersControllerTest.
 *
 * @package Tests\Feature
 */
class PositionUsersControllerTest extends BaseTenantTest
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
    public function can_add_user_to_position()
    {
        $this->loginAsSuperAdmin('api');
        $position = create_sample_position();
        $user = factory(User::class)->create();
        Event::fake();

        $response =  $this->json('POST','/api/v1/positions/' . $position->id . '/users/' . $user->id);
        $response->assertSuccessful();
        $position = json_decode($response->getContent());

        Event::assertDispatched(PositionUserStored::class,function ($event) use ($position, $user){
            return $event->position->is(Position::findOrFail($position->id)) &&
                   $event->user->is(User::findOrFail($user->id));
        });
        $this->assertSame($position->id,1);
        $this->assertEquals($position->name,'Coordinador TIC/TAC');
        $this->assertEquals($position->shortname,'Coord. TIC');
        $this->assertEquals($position->code,'TICTAC');

        $this->assertNotNull($position->created_at);
        $this->assertNotNull($position->created_at_timestamp);
        $this->assertNotNull($position->formatted_created_at);
        $this->assertNotNull($position->formatted_created_at_diff);
        $this->assertNotNull($position->updated_at);
        $this->assertNotNull($position->updated_at_timestamp);
        $this->assertNotNull($position->formatted_updated_at);
        $this->assertNotNull($position->formatted_updated_at_diff);
        $this->assertCount(1,$position->users);
        $this->assertEquals($user->name,$position->users[0]->name);

        try {
            $position = Position::findOrFail($position->id);
        } catch (\Exception $e) {
            $this->fails('Position not found at database!');
        }

        $this->assertSame($position->id,1);
        $this->assertEquals($position->name,'Coordinador TIC/TAC');
        $this->assertEquals($position->shortname,'Coord. TIC');
        $this->assertEquals($position->code,'TICTAC');
        $this->assertNotNull($position->created_at);
        $this->assertNotNull($position->created_at_timestamp);
        $this->assertNotNull($position->formatted_created_at);
        $this->assertNotNull($position->formatted_created_at_diff);
        $this->assertNotNull($position->updated_at);
        $this->assertNotNull($position->updated_at_timestamp);
        $this->assertNotNull($position->formatted_updated_at);
        $this->assertNotNull($position->formatted_updated_at_diff);

        $this->assertCount(1,$position->users);
        $this->assertEquals($user->name,$position->users[0]->name);

    }

    /**
     * @test
     * @group positions
     */
    public function positions_manager_can_add_user_to_position()
    {
        $this->loginAsPositionsManager('api');
        $position = create_sample_position();
        $user = factory(User::class)->create();
        Event::fake();

        $response =  $this->json('POST','/api/v1/positions/' . $position->id . '/users/' . $user->id);
        $response->assertSuccessful();
        $position = json_decode($response->getContent());

        Event::assertDispatched(PositionUserStored::class,function ($event) use ($position, $user){
            return $event->position->is(Position::findOrFail($position->id)) &&
                $event->user->is(User::findOrFail($user->id));
        });
        $this->assertSame($position->id,1);
        $this->assertEquals($position->name,'Coordinador TIC/TAC');
        $this->assertEquals($position->shortname,'Coord. TIC');
        $this->assertEquals($position->code,'TICTAC');

        $this->assertNotNull($position->created_at);
        $this->assertNotNull($position->created_at_timestamp);
        $this->assertNotNull($position->formatted_created_at);
        $this->assertNotNull($position->formatted_created_at_diff);
        $this->assertNotNull($position->updated_at);
        $this->assertNotNull($position->updated_at_timestamp);
        $this->assertNotNull($position->formatted_updated_at);
        $this->assertNotNull($position->formatted_updated_at_diff);
        $this->assertCount(1,$position->users);
        $this->assertEquals($user->name,$position->users[0]->name);

        try {
            $position = Position::findOrFail($position->id);
        } catch (\Exception $e) {
            $this->fails('Position not found at database!');
        }

        $this->assertSame($position->id,1);
        $this->assertEquals($position->name,'Coordinador TIC/TAC');
        $this->assertEquals($position->shortname,'Coord. TIC');
        $this->assertEquals($position->code,'TICTAC');
        $this->assertNotNull($position->created_at);
        $this->assertNotNull($position->created_at_timestamp);
        $this->assertNotNull($position->formatted_created_at);
        $this->assertNotNull($position->formatted_created_at_diff);
        $this->assertNotNull($position->updated_at);
        $this->assertNotNull($position->updated_at_timestamp);
        $this->assertNotNull($position->formatted_updated_at);
        $this->assertNotNull($position->formatted_updated_at_diff);

        $this->assertCount(1,$position->users);
        $this->assertEquals($user->name,$position->users[0]->name);

    }

    /**
     * @test
     * @group positions
     */
    public function can_remove_user_from_positions()
    {
        $this->loginAsSuperAdmin('api');

        $position = create_sample_position();
        $user = factory(User::class)->create();
        $user->assignPosition($position);
        $user = $user->fresh();
        $this->assertCount(1, $position->users);
        Event::fake();

        $response =  $this->json('DELETE','/api/v1/positions/' . $position->id . '/users/' . $user->id);
        $response->assertSuccessful();
        $position = $position->fresh();
        $this->assertCount(0, $position->users);

        Event::assertDispatched(PositionUserDeleted::class,function ($event) use ($position, $user){
            return $event->position->is(Position::findOrFail($position->id)) &&
                $event->user->is(User::findOrFail($user->id));
        });
    }

    /**
     * @test
     * @group positions
     */
    public function positions_manager_can_remove_user_from_positions()
    {
        $this->loginAsPositionsManager('api');

        $position = create_sample_position();
        $user = factory(User::class)->create();
        $user->assignPosition($position);
        $user = $user->fresh();
        $this->assertCount(1, $position->users);
        Event::fake();

        $response =  $this->json('DELETE','/api/v1/positions/' . $position->id . '/users/' . $user->id);
        $response->assertSuccessful();
        $position = $position->fresh();
        $this->assertCount(0, $position->users);

        Event::assertDispatched(PositionUserDeleted::class,function ($event) use ($position, $user){
            return $event->position->is(Position::findOrFail($position->id)) &&
                $event->user->is(User::findOrFail($user->id));
        });
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_delete_positions()
    {
        $this->login('api');

        $position = create_sample_position();
        $user = factory(User::class)->create();

        $response =  $this->json('DELETE','/api/v1/positions/' . $position->id . '/users/' . $user->id);

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_delete_positions()
    {
        $position = create_sample_position();
        $user = factory(User::class)->create();

        $response =  $this->json('DELETE','/api/v1/positions/' . $position->id . '/users/' . $user->id);

        $response->assertStatus(401);
    }
}
