<?php

namespace Tests\Feature\Tenants\Web\Users;

use App\Models\User;
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
    use RefreshDatabase,CanLogin;

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
    public function users_manager_can_see_user_password_management()
    {
        initialize_user_types();
        $manager = $this->loginAsUsersManager('web');
        $user = factory(User::class)->create();
        $response = $this->get('/users/password/' . $user->id );

        $response->assertSuccessful();
        $response->assertViewIs('tenants.users.password.show');
        $response->assertViewHas('users', function($returnedUsers) use ($manager) {
            return $returnedUsers[0]['id'] === $manager->id;
        });
        $response->assertViewHas('userTypes',function($returnedUserTypes) {
            return $returnedUserTypes[0]['name'] === 'Professor/a';
        });
        $response->assertViewHas('roles',function($returnedRoles) {
            return $returnedRoles[0]->name === 'Teacher';
        });
    }

    /**
     * @test
     * @group users
     */
    public function super_admin_can_see_users_management()
    {
        $this->loginAsSuperAdmin('web');
        $response = $this->get('/users');
        $response->assertSuccessful();
    }

    /**
     * @test
     * @group users
     */
    public function regular_user_cannot_see_users_management()
    {
        $this->login('web');
        $response = $this->get('/users');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group users
     */
    public function guest_user_cannot_see_users_management()
    {
        $response = $this->get('/users');
        $response->assertRedirect('/login');
    }

    /**
     * @test
     * @group users
     */
    public function users_manager_can_see_user()
    {
        initialize_user_types();
        $manager = $this->loginAsUsersManager('web');

        $user = factory(User::class)->create();

        $response = $this->get('/users/' . $user->id);

        $response->assertSuccessful();
        $response->assertViewIs('tenants.users.show');
        $response->assertViewHas('users', function($returnedUsers) use ($manager) {
            return $returnedUsers[0]['id'] === $manager->id;
        });
        $response->assertViewHas('userTypes',function($returnedUserTypes) {
            return $returnedUserTypes[0]['name'] === 'Professor/a';
        });
        $response->assertViewHas('roles',function($returnedRoles) {
            return $returnedRoles[0]->name === 'Teacher';
        });
        $response->assertViewHas('user',function($returnedUser) use ($user) {
            return $returnedUser->name === $user->name;
        });
    }
}
