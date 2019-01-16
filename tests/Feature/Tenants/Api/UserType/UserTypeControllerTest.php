<?php

namespace Tests\Feature\Tenants\Api\People;

use App\Models\UserType;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserTypeControllerTest.
 *
 * @package Tests\Feature
 */
class UserTypeControllerTest extends BaseTenantTest
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

    /** @test */
    public function logged_user_can_store_user_type()
    {
        $user = $this->login('api');
        $this->assertNull($user->user_type_id);
        $response = $this->json('POST','/api/v1/user/type/',[
            'type' => UserType::TEACHER
        ]);
        $response->assertSuccessful();
        $this->assertNotNull($user->user_type_id);
        $this->assertEquals(UserType::TEACHER,$user->user_type_id);
    }

    /** @test */
    public function logged_user_can_store_user_type_validation()
    {
        $user = $this->login('api');
        $this->assertNull($user->user_type_id);
        $response = $this->json('POST','/api/v1/user/type/');
        $response->assertStatus(422);
    }

    /** @test */
    public function guest_user_cannot_store_user_type_validation()
    {
        $response = $this->json('POST','/api/v1/user/type/');
        $response->assertStatus(401);
    }
}
