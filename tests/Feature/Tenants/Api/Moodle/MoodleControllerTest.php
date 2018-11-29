<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Models\User;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class MoodleControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class MoodleControllerTest extends BaseTenantTest {

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
     * @group moodle
     * @group slow
     */
    public function superadmin_can_list_moodle_users()
    {
        $this->loginAsSuperAdmin('api');
        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertTrue(is_array($result));
        $this->assertNotNull($result[0]->id);
        $this->assertNotNull($result[0]->username);
        $this->assertNotNull($result[0]->email);
    }

    /**
     * @test
     */
    public function regular_user_cannot_list_moodle_users()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_user_cannot_list_moodle_users()
    {
        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertStatus(401);
    }


}
