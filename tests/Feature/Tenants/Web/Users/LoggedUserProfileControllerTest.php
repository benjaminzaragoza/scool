<?php

namespace Tests\Feature\Tenants\Web\Users;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class LoggedUserProfileControllerTest.
 *
 * @package Tests\Feature
 */
class LoggedUserProfileControllerTest extends BaseTenantTest
{
    use RefreshDatabase,CanLogin;

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
     *
     * @test
     * @group users
     */
    public function user_can_see_his_own_profile()
    {
        $user = $this->login('web');
        $response = $this->get('/user/profile');
        $response->assertSuccessful();
        $response->assertViewIs('tenants.users.profile');
        $response->assertViewHas('user', function($returnedUser) use ($user) {
            return $returnedUser['id'] === 1 &&
                $returnedUser['name'] === $user->name;
        });
    }
}
