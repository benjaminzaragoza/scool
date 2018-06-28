<?php

namespace Tests\Unit\Tenants;

use App\GoogleGSuite\GoogleDirectory;
use App\Models\Department;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleDirectoryTest.
 *
 * @package Tests\Unit
 */
class GoogleDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

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
    public function can_get_groups()
    {
        config_google_api_for_tests();
        $groups = (new GoogleDirectory())->groups();
        $this->assertNotNull($groups);
        $this->assertTrue(is_array($groups));
        $this->assertTrue(check_google_group($groups[0]));
    }
}
