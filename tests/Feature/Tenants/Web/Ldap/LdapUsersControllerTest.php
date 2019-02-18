<?php

namespace Tests\Feature\Tenants\Web\Ldap;

use App\Models\User;
use App\Models\UserType;
use Config;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class LdapusersControllerTest.
 *
 * @package Tests\Feature
 */
class LdapusersControllerTest extends BaseTenantTest
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
     * @group ldap
     */
    public function ldap_manager_can_show_ldap_users_module()
    {
        $this->loginAsLdapManager('web');

        $response = $this->get('/ldap_users');

        $response->assertSuccessful();

        $response->assertViewIs('tenants.ldap_users.index');

        $response->assertViewHas('users',function($users) {
            return $users instanceof \Illuminate\Support\Collection;
        });

        $response->assertViewHas('localUsers');

    }




}
