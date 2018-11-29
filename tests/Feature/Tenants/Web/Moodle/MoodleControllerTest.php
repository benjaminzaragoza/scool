<?php

namespace Tests\Feature\Web\Api;

use App\Models\User;
use Cache;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class MoodleControllerTest.
 *
 * @package Tests\Feature
 */
class MoodleControllerTest extends BaseTenantTest
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

    /** @test */
    public function superadmin_can_show_moodle()
    {
        $this->withoutExceptionHandling();
        $this->loginAsSuperAdmin();
        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('scool_moodle_users',\Closure::class)
            ->andReturn(collect([]));

        Cache::shouldReceive('rememberForever')
            ->with('user_all_permissions',\Closure::class)
            ->andReturn(collect([]));

        Cache::shouldReceive('rememberForever')
            ->with('roleNames',\Closure::class)
            ->andReturn(collect([]));

        Cache::shouldReceive('rememberForever')
            ->with('permissionNames',\Closure::class)
            ->andReturn(collect([]));

        $response = $this->get('/moodle');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.moodle.index');

        $response->assertViewHas('users',function($users) {
            return $users instanceof \Illuminate\Support\Collection;
        });
    }

    /** @test */
    public function users_manager_can_show_moodle()
    {
        $this->loginAsUsersManager();
        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('scool_moodle_users',\Closure::class)
            ->andReturn(collect([]));

        Cache::shouldReceive('rememberForever')
            ->with('user_all_permissions',\Closure::class)
            ->andReturn(collect([]));

        $response = $this->get('/moodle');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.moodle.index');

        $response->assertViewHas('users',function($users) {
            return $users instanceof \Illuminate\Support\Collection;
        });
    }

    /** @test */
    public function moodle_users_manager_can_show_moodle()
    {
        $this->loginAsMoodleManager();
        $this->loginAsUsersManager();
        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('scool_moodle_users',\Closure::class)
            ->andReturn(collect([]));

        Cache::shouldReceive('rememberForever')
            ->with('user_all_permissions',\Closure::class)
            ->andReturn(collect([]));

        $response = $this->get('/moodle');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.moodle.index');

        $response->assertViewHas('users',function($users) {
            return $users instanceof \Illuminate\Support\Collection;
        });
    }

    /** @test */
    public function guest_cannot_show_moodle()
    {
        $response = $this->get('/moodle');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function regular_user_cannot_show_changelog()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/moodle');
        $response->assertStatus(403);
    }

}
