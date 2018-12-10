<?php

namespace Tests\Feature\Tenants\Api\Settings;

use App\Models\User;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class GitControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class GitControllerTest extends BaseTenantTest{

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
    public function superadmin_refresh_git_info()
    {
        $this->loginAsSuperAdmin('api');
        $result = $this->json('GET','/api/v1/git/info');
        $result->assertSuccessful();
    }

    /** @test */
    public function guest_user_cannot_refresh_git_info()
    {
        $result = $this->json('GET','/api/v1/git/info');
        $result->assertStatus(401);
    }

    /** @test */
    public function regular_user_cannot_refresh_git_info()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $result = $this->json('GET','/api/v1/git/info');
        $result->assertStatus(403);
    }

}
