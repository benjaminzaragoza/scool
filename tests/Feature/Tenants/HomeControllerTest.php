<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class HomeControllerTest.
 *
 * @package Tests\Feature
 */
class HomeControllerTest extends BaseTenantTest
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

    /** @test */
    public function show_home()
    {
        $this->withoutExceptionHandling();

        $user = $this->login();

        $response = $this->get('/home');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.home');
        $response->assertViewHas('user',function ($returnedUser) use ($user){
            return $returnedUser->is($user);
        });

    }
}
