<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ProposeFreeUsernameControllerTest.
 *
 * @package Tests\Feature
 */
class ProposeFreeUsernameControllerTest  extends BaseTenantTest
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
    public function propose_free_username()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/proposeFreeUserName/pepe/pardo');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepepardo', $result);

        factory(User::class)->create([
            'name' => 'pepepardo'
        ]);
        $response = $this->json('GET','/api/v1/proposeFreeUserName/pepe/pardo');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepepardo1', $result);

        factory(User::class)->create([
            'name' => 'pepepardo1'
        ]);
        $response = $this->json('GET','/api/v1/proposeFreeUserName/pepe/pardo');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepepardo2', $result);

        factory(User::class)->create([
            'name' => 'pepepardo2'
        ]);
        $response = $this->json('GET','/api/v1/proposeFreeUserName/pepe/pardo');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepepardo3', $result);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/pepedenommoltllarg/pardo');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepedenommpardo', $result);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/pepedenommoltllarg/pardocognommoltllarg');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepedenommpardocogno', $result);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/Pepe/Pardo');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepepardo3', $result);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/PEPE/PARDO');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepepardo3', $result);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/PepE/ParDO');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepepardo3', $result);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/Pepè/PàrDÓ');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('pepepardo3', $result);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/Maria Luisa/PàrDÓ');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('marialuisapardo', $result);

        factory(User::class)->create([
            'name' => 'marialuisapardo'
        ]);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/Maria Luisa/PàrDÓ');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('marialuisapardo1', $result);

        $response = $this->json('GET','/api/v1/proposeFreeUserName/Merçe/L·lula');

        $response->assertSuccessful();
        $result = $response->getContent();
        $this->assertEquals('mercellula', $result);

    }
}
