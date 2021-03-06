<?php

namespace Tests\Feature\Tenants\Api\UserType;

use App\Models\User;
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

    /**
     * @test
     * @group users
     */
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

    /**
     * @test
     * @group users
     */
    public function logged_user_can_store_user_type_validation()
    {
        $user = $this->login('api');
        $this->assertNull($user->user_type_id);
        $response = $this->json('POST','/api/v1/user/type/');
        $response->assertStatus(422);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_store_user_type_validation()
    {
        $response = $this->json('POST','/api/v1/user/type/');
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group users
     */
    public function users_manager_can_change_user_type ()
    {
        $this->loginAsUsersManager('api');
        initialize_user_types();
        $user = factory(User::class)->create([
            'user_type_id' => 1
        ]);
        $this->assertEquals('teacher', $user->user_type);
        $response = $this->json('PUT','/api/v1/user/' . $user->id . '/type/2');
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertEquals('student', $user->user_type);
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_change_user_type ()
    {
        $this->login('api');
        initialize_user_types();
        $user = factory(User::class)->create([
            'user_type_id' => 1
        ]);
        $this->assertEquals('teacher', $user->user_type);
        $response = $this->json('PUT','/api/v1/user/' . $user->id . '/type/2');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_change_user_type ()
    {
        initialize_user_types();
        $user = factory(User::class)->create([
            'user_type_id' => 1
        ]);
        $this->assertEquals('teacher', $user->user_type);
        $response = $this->json('PUT','/api/v1/user/' . $user->id . '/type/2');
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group users
     */
    public function users_manager_can_remove_user_type ()
    {
        $this->loginAsUsersManager('api');
        initialize_user_types();
        $user = factory(User::class)->create([
            'user_type_id' => 1
        ]);
        $this->assertEquals('teacher', $user->user_type);
        $response = $this->json('DELETE','/api/v1/user/' . $user->id . '/type');
        $response->assertSuccessful();
        $user = $user->fresh();
        $this->assertNull($user->user_type);
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_remove_user_type ()
    {
        $this->login('api');
        initialize_user_types();
        $user = factory(User::class)->create([
            'user_type_id' => 1
        ]);
        $this->assertEquals('teacher', $user->user_type);
        $response = $this->json('DELETE','/api/v1/user/' . $user->id . '/type');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_remove_user_type ()
    {
        initialize_user_types();
        $user = factory(User::class)->create([
            'user_type_id' => 1
        ]);
        $this->assertEquals('teacher', $user->user_type);
        $response = $this->json('DELETE','/api/v1/user/' . $user->id . '/type');
        $response->assertStatus(401);
    }
}
