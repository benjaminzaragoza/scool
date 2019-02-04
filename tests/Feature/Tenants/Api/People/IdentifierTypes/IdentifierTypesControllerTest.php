<?php

namespace Tests\Feature\Tenants\Api\People\IdentifierTypes;

use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class PeopleControllerTest.
 *
 * @package Tests\Feature
 */
class IdentifierTypesControllerTest extends BaseTenantTest
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
     * @group people
     *
     */
    public function can_list_identifier_types()
    {
        seed_identifier_types();
        $response = $this->json('GET','/api/v1/identifier_types/');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertCount(4,$result);
        $this->assertEquals(1,$result[0]->id);
        $this->assertEquals('NIF',$result[0]->name);
        $this->assertEquals(2,$result[1]->id);
        $this->assertEquals('Pasaporte',$result[1]->name);
    }

}
