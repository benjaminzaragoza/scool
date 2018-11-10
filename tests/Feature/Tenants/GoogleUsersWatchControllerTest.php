<?php

namespace Tests\Feature\Tenants;

use App\Jobs\WatchGoogleUsers;
use Illuminate\Contracts\Console\Kernel;
use App\Models\User;
use Config;
use Queue;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleUsersWatchControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleUsersWatchControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group google
     */
    public function watch_google_users()
    {
        Queue::fake();

        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');
        Config::set('app.url', 'https://iesebre.scool.cat');
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('POST','/api/v1/gsuite/users/watch');
        $response->assertSuccessful();

        Queue::assertPushed(WatchGoogleUsers::class);
    }

    /** @test */
    public function user_cannot_watch_google_users()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/gsuite/users/watch');
        $response->assertStatus(403);
    }
}
