<?php

namespace Tests\Feature\Tenants;


use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;

/**
 * Class UserEmailsControllerTest.
 *
 * @package Tests\Feature
 */
class UserEmailsControllerTest extends BaseTenantTest
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
    public function can_get_user_by_email()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager, 'api');

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

    /** @test */
    public function regular_user_cannot_get_user_by_email()
    {
        $regularUser = factory(User::class)->create();
        $this->actingAs($regularUser, 'api');

        $user = factory(User::class)->create();

        $response = $this->json('GET', '/api/v1/users/email/' . $user->email);

        $response->assertStatus(403);
    }

    /** @test */
    public function can_get_user_by_email_404()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager, 'api');

        factory(User::class)->create();

        $response = $this->json('GET', '/api/v1/users/email/noexistingemail@gmail.com');

        $response->assertStatus(404);
    }


}
