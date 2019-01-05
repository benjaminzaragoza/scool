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
        $this->loginAsSuperAdmin();
        $response = $this->get('/positions');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.positions.index');
        $response->assertViewHas('positions', function ($returnedPositions) {
            return
                count($returnedPositions) === 2 &&
                $returnedPositions[0]['name'] === 'Coordinador TIC/TAC';
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
