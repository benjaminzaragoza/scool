<?php

namespace Tests\Feature\Tenants;

use Illuminate\Contracts\Console\Kernel;
use Config;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleSuiteConnectionControllerTest.
 *
 * @package Tests\Feature
 */
class GoogleSuiteConnectionControllerTest extends BaseTenantTest
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
     * @group working
     * @group gsuite
     */
    public function can_connect_to_gsuite()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $response = $this->json('GET','/api/v1/gsuite/test_connection');
        $response->assertSuccessful();

        $this->assertEquals('Ok',json_decode($response->getContent())->result);
    }
}
