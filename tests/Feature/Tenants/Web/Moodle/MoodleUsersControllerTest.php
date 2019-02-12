<?php

namespace Tests\Feature\Web\Moodle;

use App\Models\User;
use Cache;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class MoodleUsersControllerTest.
 *
 * @package Tests\Feature
 */
class MoodleUsersControllerTest extends BaseTenantTest
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
     * @group moodle
     */
    public function superadmin_can_show_moodle()
    {
        $this->loginAsSuperAdmin();
        Cache::shouldReceive('put');
        Cache::shouldReceive('has');
        Cache::shouldReceive('remember')
            ->once()
            ->with('git_info',5,\Closure::class)
            ->andReturn(collect([]));
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

        $response = $this->get('/moodle/users');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.moodle.index');

        $response->assertViewHas('users',function($users) {
            return $users instanceof \Illuminate\Support\Collection;
        });

        $response->assertViewHas('localUsers',function($users) {
            return $users instanceof \Illuminate\Support\Collection;
        });
    }

    /**
     * @test
     * @group moodle
     */
    public function users_manager_can_show_moodle()
    {
        $this->loginAsUsersManager();
        Cache::shouldReceive('put');
        Cache::shouldReceive('has');
        Cache::shouldReceive('remember')
            ->once()
            ->with('git_info',5,\Closure::class)
            ->andReturn(collect([]));
        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('scool_moodle_users',\Closure::class)
            ->andReturn(collect([]));

        Cache::shouldReceive('rememberForever')
            ->with('user_all_permissions',\Closure::class)
            ->andReturn(collect([]));

        $response = $this->get('/moodle/users');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.moodle.index');

        $response->assertViewHas('users',function($users) {
            return $users instanceof \Illuminate\Support\Collection;
        });
    }

    /**
     * @test
     * @group moodle
     */
    public function moodle_users_manager_can_show_moodle()
    {
        $this->loginAsMoodleManager();
        Cache::shouldReceive('put');
        Cache::shouldReceive('has');
        Cache::shouldReceive('remember')
            ->once()
            ->with('git_info',5,\Closure::class)
            ->andReturn(collect([]));
        Cache::shouldReceive('rememberForever')
            ->once()
            ->with('scool_moodle_users',\Closure::class)
            ->andReturn(collect([]));

        Cache::shouldReceive('rememberForever')
            ->with('user_all_permissions',\Closure::class)
            ->andReturn(collect([]));

        $response = $this->get('/moodle/users');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.moodle.index');

        $response->assertViewHas('users',function($users) {
            return $users instanceof \Illuminate\Support\Collection;
        });
    }

    /**
     * @test
     * @group moodle
     */
    public function guest_cannot_show_moodle()
    {
        $response = $this->get('/moodle/users');
        $response->assertRedirect('/login');
    }

    /**
     * @test
     * @group moodle
     */
    public function regular_user_cannot_show_changelog()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->get('/moodle/users');
        $response->assertStatus(403);
    }

}
