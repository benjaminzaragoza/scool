<?php

namespace Tests\Feature\Tenants\Api\Users;

use App\Events\UserMobileUpdated;
use App\Models\User;
use Carbon\Carbon;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserMobileControllerTest.
 *
 * @package Tests\Feature
 */
class UserMobileControllerTest extends BaseTenantTest
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
    public function can_get_user_by_mobile()
    {
        $this->loginAsUsersManager('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->json('GET', '/api/v1/users/mobile/' . $user->mobile);

        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertEquals(2,$result->id);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $this->assertEquals('pepepardojeans@gmail.com',$result->email);
        $this->assertNull($result->mobile_verified_at);
        $this->assertEquals('654789524',$result->mobile);
        $this->assertNull($result->last_login);
        $this->assertNull($result->last_login_ip);
        $this->assertNotNull($result->created_at);
        $this->assertNotNull($result->updated_at);
        $this->assertEmpty($result->roles);
        $this->assertNotNull($result->formatted_created_at);
        $this->assertNotNull($result->formatted_updated_at);
        $this->assertEquals(0,$result->admin);
        $this->assertEquals('Ay',$result->hashid);

        $user = $user->fresh();
        $this->assertEquals(2,$user->id);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('pepepardojeans@gmail.com',$user->email);
        $this->assertNull($user->mobile_verified_at);
        $this->assertEquals('654789524',$user->mobile);
        $this->assertNull($user->last_login);
        $this->assertNull($user->last_login_ip);
        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
        $this->assertEmpty($user->roles);
        $this->assertNotNull($user->formatted_created_at);
        $this->assertNotNull($user->formatted_updated_at);
        $this->assertEquals(0,$user->admin);
        $this->assertEquals('Ay',$user->hashid);
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_get_user_by_mobile()
    {
        $this->login('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->json('GET', '/api/v1/users/mobile/' . $user->mobile);

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function can_get_user_by_mobile_404()
    {
        $this->loginAsUsersManager('api');
        factory(User::class)->create();

        $response = $this->json('GET', '/api/v1/users/mobile/999999999');

        $response->assertStatus(404);
    }

    /** @test */
    public function can_update_user_mobile()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'mobile' => '666584789',
            'mobile_verified_at' => Carbon::now()
        ]);

        Event::fake();
        $response = $this->json('PUT', '/api/v1/users/' . $user->id . '/mobile', [
            'mobile' => '666555444'
        ]);
        $response->assertSuccessful();
        Event::assertDispatched(UserMobileUpdated::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });

        $result = json_decode($response->getContent());
        $this->assertEquals($user->id,$result->id);
        $this->assertEquals('666555444',$result->mobile);

        $newUser = $user->fresh();
        $this->assertEquals($newUser->id,$user->id);
        $this->assertEquals('666555444',$newUser->mobile);
        $this->assertNull($newUser->mobile_verified_at);
    }

    /** @test */
    public function can_update_user_mobile_validation()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'mobile' => '666584789',
        ]);
        $response = $this->json('PUT', '/api/v1/users/' . $user->id . '/mobile');
        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_cannot_update_mobile()
    {
        $this->login('api');
        $user = factory(User::class)->create([
            'mobile' => '666584789',
        ]);
        $response = $this->json('PUT', '/api/v1/users/' . $user->id . '/mobile');
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_update_mobile()
    {
        $user = factory(User::class)->create([
            'mobile' => '666584789',
        ]);
        $response = $this->json('PUT', '/api/v1/users/' . $user->id . '/mobile');
        $response->assertStatus(401);
    }

}
