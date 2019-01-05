<?php

namespace Tests\Feature\Web\Curriculum;

use App\Models\Course;
use App\Models\SubjectGroup;
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
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin();
        $response = $this->get('/positions');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.positions.index');
        $response->assertViewHas('positions', function ($returnedPositions) {
            dump($returnedPositions);
            return
                count($returnedPositions) === 4 &&
                $returnedPositions[0]['name'] === 'Desenvolupament Aplicacions Multiplataforma' &&
                $returnedPositions[0]['shortname'] === 'Des. Apps Multiplataforma' &&
                $returnedPositions[0]['code'] === "DAM" &&
                $returnedPositions[0]['created_at'] !== null &&
                $returnedPositions[0]['created_at_timestamp'] !== null &&
                $returnedPositions[0]['formatted_created_at'] !== null &&
                $returnedPositions[0]['formatted_created_at_diff'] !== null &&
                $returnedPositions[0]['updated_at_timestamp'] !== null &&
                $returnedPositions[0]['formatted_updated_at'] !== null &&
                $returnedPositions[0]['formatted_updated_at_diff'] !== null;
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
