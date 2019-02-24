<?php

namespace Tests\Feature\Tenants\Api\Users\Password;

use App\Events\Users\Password\UserPasswordChangedByManager;
use App\Models\User;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserPasswordControllerTest.
 *
 * @package Tests\Feature
 */
class UserPasswordControllerTest extends BaseTenantTest
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
    public function users_manager_can_change_user_password()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans'
        ]);
        $originalPassword = $user->password;
        Event::fake();
        $options = [
            'force' => true,
            'email' => true,
            'ldap' => true,
            'moodle' => true,
            'google' => true
        ];
        $response = $this->json('PUT','/api/v1/user/' . $user->id . '/password' , [
            'password' => 'NEWPASSWORD',
            'options' => $options
        ]);
        Event::assertDispatched(UserPasswordChangedByManager::class, function ($event) use ($user, $options) {
            return $event->user->id === $user->id &&
                $event->options['force'] === true &&
                $event->options['email'] === true &&
                $event->options['ldap'] === true &&
                $event->options['moodle'] === true &&
                $event->options['google'] === true;
        });
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($user->id,$result->id);
        $this->assertEquals('Pepe Pardo Jeans',$result->name);
        $user = $user->fresh();
        $this->assertNotEquals($originalPassword,$user->password);
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_change_user_password()
    {
        $this->login('api');
        $user = factory(User::class)->create();
        $response = $this->json('PUT','/api/v1/user/' . $user->id . '/password' , [
            'password' => 'NEWPASSWORD',
            'options' => [
                'force' => true,
                'email' => true,
                'ldap' => true,
                'moodle' => true,
                'google' => true
            ]
        ]);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_change_user_password()
    {
        $user = factory(User::class)->create();
        $response = $this->json('PUT','/api/v1/user/' . $user->id . '/password' , [
            'password' => 'NEWPASSWORD',
            'options' => [
                'force' => true,
                'email' => true,
                'ldap' => true,
                'moodle' => true,
                'google' => true
            ]
        ]);
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group users
     */
    public function users_manager_can_change_user_password_validation()
    {
        $this->loginAsUsersManager('api');
        $user = factory(User::class)->create();
        $response = $this->json('PUT','/api/v1/user/' . $user->id . '/password' , []);
        $response->assertStatus(422);
    }
}
