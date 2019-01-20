<?php

namespace Tests\Feature\Tenants\Api\Users;

use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserEmailsControllerTest.
 *
 * @package Tests\Feature
 */
class UserEmailsControllerTest extends BaseTenantTest
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
    public function can_get_user_by_email()
    {
        $this->loginAsUsersManager('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com',
            'mobile' => '654789524'
        ]);

        $response = $this->json('GET', '/api/v1/users/email/' . $user->email);

        $response->assertSuccessful();

        $user = json_decode($response->getContent());
        $this->assertEquals(2,$user->id);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('pepepardojeans@gmail.com',$user->email);
        $this->assertNull($user->email_verified_at);
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
    public function regular_user_cannot_get_user_by_email()
    {
        $this->login('api');

        $user = factory(User::class)->create();

        $response = $this->json('GET', '/api/v1/users/email/' . $user->email);

        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function can_get_user_by_email_404()
    {
        $this->loginAsUsersManager('api');
        factory(User::class)->create();

        $response = $this->json('GET', '/api/v1/users/email/noexistingemail@gmail.com');

        $response->assertStatus(404);
    }

    /** @test */
    public function can_update_user_email()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'email' => 'oldemail@gmail.com',
            'email_verified_at' => Carbon::now()
        ]);
        $response = $this->json('PUT', '/api/v1/users/' . $user->id . '/email', [
            'email' => 'newemail@gmail.com'
        ]);
        $response->assertSuccessful();

        $result = json_decode($response->getContent());
        $this->assertEquals($user->id,$result->id);
        $this->assertEquals('newemail@gmail.com',$result->email);

        $newUser = $user->fresh();
        $this->assertEquals($newUser->id,$user->id);
        $this->assertEquals('newemail@gmail.com',$newUser->email);
        $this->assertNull($newUser->email_verified_at);
    }

    /** @test */
    public function can_update_user_email_validation()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'email' => 'oldemail@gmail.com'
        ]);
        $response = $this->json('PUT', '/api/v1/users/' . $user->id . '/email');
        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_cannot_update_email()
    {
        $this->login('api');
        $user = factory(User::class)->create([
            'email' => 'oldemail@gmail.com'
        ]);
        $response = $this->json('PUT', '/api/v1/users/' . $user->id . '/email');
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_update_email()
    {
        $user = factory(User::class)->create([
            'email' => 'oldemail@gmail.com'
        ]);
        $response = $this->json('PUT', '/api/v1/users/' . $user->id . '/email');
        $response->assertStatus(401);
    }

}
