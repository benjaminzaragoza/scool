<?php

namespace Tests\Feature\Web\Changelog;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class ChangelogUserControllerTest.
 *
 * @package Tests\Feature
 */
class ChangelogUserControllerTest extends BaseTenantTest
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
    public function show_changelog_for_an_specific_user()
    {
        $logs = sample_logs();

        $user = User::findOrFail(1);
        $this->actingAs($user);
        $response = $this->get('/changelog/user/' . $user->id);
        $response->assertSuccessful();

        $response->assertViewIs('tenants.changelog.users.index');
        $response->assertViewHas('logs', function ($returnedLogs) use ($logs) {
            return
                $returnedLogs[0]['user_name'] === $logs[0]['user']->name &&
                $returnedLogs[0]['color'] === 'teal' &&
                $returnedLogs[0]['action_type'] === 'update' &&
                $returnedLogs[0]['text'] === "Ha creat la incidència TODO_LINK_INCIDENCIA" &&
                $returnedLogs[0]['icon'] === 'home' &&
                count($returnedLogs) === 1;
        });
        $response->assertViewHas('users');
        $response->assertViewHas('user', function ($returnedUser) use ($user) {
            return $returnedUser->is($user);
        });
    }

    /** @test */
    public function cannot_changelog_for_an_specific_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/changelog/user/nonexistinguser');
        $response->assertStatus(404);
    }

    /** @test */
    public function guest_changelog_for_an_specific_user()
    {
        $user = factory(User::class)->create();
        $response = $this->get('/changelog/user/' . $user->id);
        $response->assertRedirect('/login');
    }
}
