<?php

namespace Tests\Feature\Tenants;

use App\Models\GoogleUser;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class GoogleUsersControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleUsersControllerTest extends BaseTenantTest
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
     * @group slow
     * @group google
     */
    public function show_google_users_by_id()
    {
        config_google_api();
        tune_google_client();
        $this->loginAsUsersManager('web');
        try {
            if (!google_user_exists('provaborrar777@iesebre.com')) {
                $googleUser = google_user_create([
                    'givenName' =>  'Nom',
                    'familyName' =>  'Cognom',
                    'primaryEmail' =>  'provaborrar777@iesebre.com'
                ]);
            }
        } catch (\Exception $e) {

        }
        $googleUser = google_user_get('provaborrar777@iesebre.com');
        $response = $this->get('/google_users/' . $googleUser->id);
        $response->assertSuccessful();
    }

    /**
     * @ test
     * @group slow
     */
    public function show_google_users()
    {
        config_google_api();
        tune_google_client();
        $usersManager = create(User::class);
        $this->actingAs($usersManager);
        $role = Role::firstOrCreate(['name' => 'UsersManager']);
        Config::set('auth.providers.users.model', User::class);
        $usersManager->assignRole($role);

        $response = $this->get('google_users');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.google_users.index');
        $response->assertViewHas('users', function($users) {
            return google_user_check($users[0]);
        });
        $response->assertViewHas('localUsers');
    }

    /** @test */
    public function regular_user_cannot_show_google_users()
    {
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('google_users');

        $response->assertStatus(403);
    }

}
