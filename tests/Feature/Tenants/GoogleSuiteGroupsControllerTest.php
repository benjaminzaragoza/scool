<?php

namespace Tests\Feature\Tenants;

use App\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleSuiteGroupsControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class GoogleSuiteGroupsControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group working
     */
    public function show_google_admin_groups()
    {
        $this->withoutExceptionHandling();
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('GET','/api/v1/gsuite/groups/');
        $response->assertStatus(403);
    }

    public function show_google_admin_group()
    {
        // IMPORTANT: NO MOSTRA ELS MEMBRES D'UN GRUP

//        $this->withoutExceptionHandling();
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $group_email = 'claustre@iesebre.com';
        $response = $this->json('GET','/api/v1/gsuite/groups/' . $group_email);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group working
     */
    public function user_cannot_show_google_admin_groups()
    {
//        Config::set('google.service.enable', true);
//        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
//        Config::set('google.admin_email', 'sergitur@iesebre.com');
//
//        $user = create(User::class);
//        $this->actingAs($user,'api');
//
//        $response = $this->json('GET','/api/v1/gsuite/users/');
//        $response->assertStatus(403);
    }



}
