<?php

namespace Tests\Feature\Web\Api;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class HomeControllerTest.
 *
 * @package Tests\Feature
 */
class HomeControllerTest extends BaseTenantTest
{
    use RefreshDatabase;

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

    /** @test */
    public function show_changelog()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/changelog');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.changelog.index');
        $response->assertViewHas('logs');
        $response->assertViewHas('users');
    }
}
