<?php

namespace Tests\Unit;

use App\Tenant;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TenantTest.
 *
 * @package Tests\Unit
 */
class TenantTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_add_tenant_to_user()
    {
        $user = $this->create_user_with_tenant();

        $this->assertEquals('ACME SL',$user->tenants()->first()->name);
        $this->assertEquals('acme',$user->tenants()->first()->subdomain);
        $this->assertEquals('localhost',$user->tenants()->first()->hostname);
        $this->assertEquals('acme',$user->tenants()->first()->username);
        $this->assertEquals('SECRET',$user->tenants()->first()->password);
    }

    private function create_user_with_tenant() {
        $user = create(User::class);
        $tenant = create(Tenant::class, [
            'name' => 'ACME SL',
            'subdomain' => 'acme',
            'email_domain' => 'acme.com',
            'hostname' => 'localhost',
            'database' => 'acme',
            'username' => 'acme',
            'password' => 'SECRET',
            'port' => 3306
        ]);
        $user->addTenant($tenant);
        return $user;
    }
}
