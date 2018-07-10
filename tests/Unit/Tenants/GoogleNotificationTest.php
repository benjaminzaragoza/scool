<?php

namespace Tests\Unit\Tenants;

use App\Models\GoogleNotification;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleNotificationTest.
 *
 * @package Tests\Unit\Tenants
 */
class GoogleNotificationTest extends TestCase
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
    public function validate_request()
    {
        $request = null;
        $this->assertFalse(GoogleNotification::validate($request));
    }
}
