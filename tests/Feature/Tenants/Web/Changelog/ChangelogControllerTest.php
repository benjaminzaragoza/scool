<?php

namespace Tests\Feature\Web\Api;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class ChangelogControllerTest.
 *
 * @package Tests\Feature
 */
class ChangelogControllerTest extends BaseTenantTest
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
        $logs = sample_logs();
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/changelog');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.changelog.index');
        $response->assertViewHas('logs', function ($returnedLogs) use ($logs) {
            return
                $returnedLogs[0]['user']->name === $logs[0]['user']->name &&
                $returnedLogs[0]['color'] === 'teal' &&
                $returnedLogs[0]['action_type'] === 'update' &&
                $returnedLogs[0]['text'] === "Ha creat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[0]['icon'] === 'home' &&
                $returnedLogs[1]['text'] === "Ha modificat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[1]['action_type'] === 'update' &&
                $returnedLogs[2]['text'] === "Ha modificat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[2]['action_type'] === 'update' &&
                $returnedLogs[3]['text'] === "BLA BLA BLA";
        });
        $response->assertViewHas('users');
    }
}
