<?php

namespace Tests\Unit\Tenants;

use App\Models\Role;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class RoleTest.
 *
 * @package Tests\Unit
 */
class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

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
    public function map()
    {
        $role = Role::create([
            'name' => 'Rol1',
            'guard_name' => 'web'
        ]);

        $mappedRole = $role->map();

        $this->assertSame(1,$mappedRole['id']);
        $this->assertSame('Rol1',$mappedRole['name']);
        $this->assertSame('web',$mappedRole['guard_name']);
        $this->assertSame('roles',$mappedRole['api_uri']);

        $this->assertNotNull($mappedRole['created_at']);
        $this->assertNotNull($mappedRole['updated_at']);
        $this->assertNotNull($mappedRole['created_at_timestamp']);
        $this->assertNotNull($mappedRole['updated_at_timestamp']);
        $this->assertNotNull($mappedRole['formatted_created_at']);
        $this->assertNotNull($mappedRole['formatted_updated_at']);
        $this->assertNotNull($mappedRole['formatted_created_at_diff']);
        $this->assertNotNull($mappedRole['formatted_updated_at_diff']);
    }
}
