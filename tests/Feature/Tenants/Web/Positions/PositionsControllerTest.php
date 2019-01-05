<?php

namespace Tests\Feature\Web\Positions;

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
    public function show_positions_module()
    {
        initialize_positions();
        $user = $this->loginAsSuperAdmin();
        $response = $this->get('/positions');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.positions.index');
        $response->assertViewHas('positions', function ($returnedPositions) {
            return
                count($returnedPositions) === 2 &&
                $returnedPositions[0]['name'] === 'Coordinador TIC/TAC' &&
                $returnedPositions[0]['shortname'] === 'Coord. TIC' &&
                $returnedPositions[0]['api_uri'] === 'positions' &&
                $returnedPositions[0]['users'] !== null &&
                $returnedPositions[0]['shortname'] === 'Coord. TIC' &&
                $returnedPositions[0]['code'] === 'TICTAC';
        });
        $response->assertViewHas('users', function ($returnedUsers) use ($user) {
            return
                count($returnedUsers) === 1 &&
                $returnedUsers[0]['name'] === $user->name;
        });
    }

    /**
     * @test
     * @group positions
     */
    public function regular_user_cannot_show_positions_module()
    {
        $this->login();
        $response = $this->get('/positions');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group positions
     */
    public function guest_user_cannot_show_positions_module()
    {
        $response = $this->get('/positions');
        $response->assertRedirect('login');
    }

}
