<?php

namespace Tests\Unit\Tenants;

use App\Models\Permission;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class PermissionTest.
 *
 * @package Tests\Unit
 */
class PermissionTest extends TestCase
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
        $role = Permission::create([
            'name' => 'Rol1',
            'guard_name' => 'web'
        ]);

        $mappedPermission = $role->map();

        $this->assertSame(1,$mappedPermission['id']);
        $this->assertSame('Rol1',$mappedPermission['name']);
        $this->assertSame('web',$mappedPermission['guard_name']);
        $this->assertSame('permissions',$mappedPermission['api_uri']);

        $this->assertNotNull($mappedPermission['formatted_updated_at']);
        $this->assertNotNull($mappedPermission['formatted_created_at_diff']);
        $this->assertNotNull($mappedPermission['formatted_updated_at_diff']);
    }
}
