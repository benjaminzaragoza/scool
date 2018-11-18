<?php

namespace Tests\Feature;
use Adldap\Laravel\Events\Authenticated;
use App\Models\Log;
use App\Models\User;
use Config;
use Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\BaseTenantTest;

/**
 * Class LoginControllerTest
 * @package Tests\Feature
 */
class LoginControllerTest extends BaseTenantTest
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
     */
    public function can_login_a_user()
    {
        $user = factory(User::class)->create([
            'email' => 'prova@gmail.com'
        ]);

        $this->assertNull(Auth::user());

        $response = $this->post('/login',[
            'email' => 'prova@gmail.com', //$user->email
            'password' => 'secret' // password per defecte de les factories Laravel
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/home');
        $this->assertNotNull(Auth::user());
        $this->assertEquals('prova@gmail.com',Auth::user()->email);
        $this->assertTrue(Auth::user()->is($user));
    }

    /**
     * @test
     */
    public function cannot_login_an_user_with_incorrect_password()
    {
        factory(User::class)->create([
            'email' => 'prova@gmail.com'
        ]);
        $this->assertNull(Auth::user());
        $response = $this->post('/login',[
            'email' => 'prova@gmail.com',
            'password' => 'asdjaskdlasdasd0798asdjh'
        ]);
        $response->assertStatus(422);
        $this->assertNull(Auth::user());
    }

    /**
     * @test
     */
    public function cannot_login_an_user_with_incorrect_user()
    {
        factory(User::class)->create([
            'email' => 'prova@gmail.com'
        ]);

        $this->assertNull(Auth::user());

        $response = $this->post('/login',[
            'email' => 'provaasdasdasd@gmail.com', //$user->email
            'password' => 'secret'
        ]);
        $response->assertStatus(422);
        $this->assertNull(Auth::user());
    }

    /**
     * @test
     */
    public function can_logout_a_user()
    {
        $user = factory(User::class)->create([
            'email' => 'prova@gmail.com'
        ]);
        Auth::login($user);

        $this->assertNotNull(Auth::user());
        $this->assertTrue(Auth::user()->is($user));
        $response = $this->post('/logout');
        $response->assertStatus(302);
        $response->assertRedirect('/');
        $this->assertNull(Auth::user());
    }
}
