<?php

namespace Tests\Feature;

use App\Events\TenantCreated;
use App\Events\TenantDeleted;
use App\Events\TenantUpdated;
use App\User;
use Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use View;

/**
 * Class UserTenantsControllerTest.
 *
 * @package Tests\Feature
 */
class UserTenantsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_user_can_see_create_tenant_form()
    {
        $user = create(User::class);
        $this->actingAs($user);
        View::share('user',$user);

        $response = $this->get('/home');
        $response->assertViewIs('tenant.tenants');
        $response->assertViewHas('tenants');
        $response->assertSuccessful();
    }

    /** @test */
    public function logged_user_can_see_his_tenants()
    {
        $user = create(User::class);

        $user->addTenant(create_tenant('Acme SL','acme'));
        $user->addTenant(create_tenant('Purgas SL','purgas'));
        $user->addTenant(create_tenant('Prova SL','prova'));

        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/tenant');

        $response->assertSuccessful();

        $this->assertCount(3,json_decode($response->getContent()));

        $response->assertJsonFragment(
            [
                'name' => 'Acme SL',
                'subdomain' => 'acme',
                'hostname' => 'localhost',
                'username' => 'acme',
                'database' => 'acme',
                'port' => '3306'
            ],
            [
                'name' => 'Purgas SL',
                'subdomain' => 'purgas',
                'hostname' => 'localhost',
                'username' => 'purgas',
                'database' => 'purgas',
                'port' => '3306'
            ],
            [
                'name' => 'Prova SL',
                'subdomain' => 'prova',
                'hostname' => 'localhost',
                'username' => 'prova',
                'database' => 'prova',
                'port' => '3306'
            ]
        );

        $response->assertJsonMissing(
            [
                'password'
            ]
        );
    }

    /** @test */
    public function logged_user_can_create_tenant()
    {
        $this->withoutExceptionHandling();
        $user = create(User::class);
        $this->actingAs($user,'api');

        Event::fake();

        $response = $this->json('POST','/api/v1/tenant', [
            'name' => 'ACME SL',
            'subdomain' => 'acme',
            'email_domain' => 'acme.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response->assertSuccessful();
        $response->assertJsonFragment(
            [
                'name' => 'ACME SL',
                'subdomain' => 'acme',
                'username' => 'acme',
                'hostname' => 'localhost',
                'database' => 'acme',
                'port' => 3306,
                'user_id' => 1
            ]);

        Event::assertDispatched(TenantCreated::class, function ($e) {
            return $e->tenant->name === 'ACME SL' && $e->tenant->subdomain === 'acme';
        });

        $this->assertEquals('ACME SL',$user->tenants()->first()->name);
        $this->assertEquals('acme',$user->tenants()->first()->subdomain);
        $this->assertEquals('localhost',$user->tenants()->first()->hostname);
        $this->assertEquals('acme',$user->tenants()->first()->database);
        $this->assertEquals('acme',$user->tenants()->first()->username);
        $this->assertEquals(3306,$user->tenants()->first()->port);
        $this->assertTrue($user->tenants()->first()->password != '');
    }

    /** @test */
    public function logged_user_test_create_tenant_validation()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/tenant');

        $response->assertStatus(422);
    }

    /** @test */
    public function logged_user_can_delete_tenant()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $tenant = create_tenant('Acme SL','acme');
        $user->addTenant($tenant);
        $this->assertCount(1, $user->tenants);

        Event::fake();
        $response = $this->json('DELETE','/api/v1/tenant/' . $tenant->id);

        $response->assertSuccessful();

        Event::assertDispatched(TenantDeleted::class, function ($e) use ($tenant) {
            return $e->tenant->name === $tenant->name && $e->tenant->subdomain === $tenant->subdomain;
        });

        $user = $user->fresh();
        $this->assertCount(0, $user->tenants);
    }

    /** @test */
    public function logged_user_can_update_tenant_name()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $tenant = create_tenant('Acme SL','acme');
        $user->addTenant($tenant);

        $response = $this->json('PUT','/api/v1/tenant/' . $tenant->id . '/name'  , [
            'name' => 'New ACME SL'
        ]);

        $response->assertSuccessful();

        $response->assertJsonFragment([
            'name' => 'New ACME SL',
            'subdomain' => 'acme',
            'hostname' => 'localhost',
            'username' => 'acme',
            'database' => 'acme',
            'port' => '3306',
            'user_id' => '1',

        ]);

        $user = $user->fresh();
        $this->assertEquals('New ACME SL', $user->tenants()->first()->name);
    }

    /** @test */
    public function logged_user_can_update_tenant_subdomain()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $tenant = create_tenant('Acme SL','acme');
        $user->addTenant($tenant);

        Event::fake();

        $response = $this->json('PUT','/api/v1/tenant/' . $tenant->id . '/subdomain'  , [
            'subdomain' => 'newacme'
        ]);

        $response->assertSuccessful();

        Event::assertDispatched(TenantUpdated::class, function ($e) use ($tenant) {
            return $e->tenant->name === $tenant->name && $e->tenant->subdomain === 'newacme';
        });

        $response->assertJsonFragment([
            'name' => 'Acme SL',
            'subdomain' => 'newacme',
            'hostname' => 'localhost',
            'username' => 'acme',
            'database' => 'acme',
            'port' => '3306',
            'user_id' => '1'
        ]);

        $user = $user->fresh();
        $this->assertEquals('newacme', $user->tenants()->first()->subdomain);
    }
}
