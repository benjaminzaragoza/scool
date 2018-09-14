<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Auth;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class LastLoginTest.
 *
 * @package Tests\Feature
 */
class LastLoginTest extends BaseTenantTest
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
    public function last_login_is_established_after_login()
    {
        $user = factory(User::class)->create([
            'email' => 'user@gmail.com',
            'password' => bcrypt('123465')
        ]);

        $this->assertNull($user->email_verified_at);

        Auth::attempt([ 'email' => 'user@gmail.com', 'password' => '123465']);

        $user = $user->fresh();
        $this->assertNotNull($user->last_login);
        $this->assertNotNull($user->last_login_ip);
    }
}
