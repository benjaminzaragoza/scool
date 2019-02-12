<?php

namespace Tests\Feature\Tenants\Api\People\Identifier;

use App\Models\Identifier;
use App\Models\Person;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class CheckIdentifierControllerTest.
 *
 * @package Tests\Feature
 */
class CheckIdentifierControllerTest extends BaseTenantTest
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
    public function can_check_identifier()
    {
        $this->loginAsUsersManager('api');
        seed_identifier_types();
        $response = $this->json('POST', '/api/v1/identifier/check', [
            'identifier_type_id' => 1,
            'identifier_value' => '49159238G'
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertFalse($result);

        Identifier::create([
            'type_id' => 1,
            'value' => '70790031X'
        ]);
        $response = $this->json('POST', '/api/v1/identifier/check', [
            'identifier_type_id' => 1,
            'identifier_value' => '70790031X'
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertTrue($result);
    }

    /**
     * @test
     * @group people
     *
     */
    public function can_check_identifier_validation()
    {
        $this->loginAsUsersManager('api');
        $response = $this->json('POST', '/api/v1/identifier/check', []);
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group people
     *
     */
    public function regular_user_cannot_check_identifier()
    {
        $this->login('api');
        $response = $this->json('POST', '/api/v1/identifier/check', [
            'identifier_type_id' => 1,
            'identifeir_type_value' => '49159238G'
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group people
     *
     */
    public function guest_user_cannot_check_identifier()
    {
        $response = $this->json('POST', '/api/v1/identifier/check', [
            'identifier_type_id' => 1,
            'identifeir_type_value' => '49159238G'
        ]);
        $response->assertStatus(401);
    }
}
