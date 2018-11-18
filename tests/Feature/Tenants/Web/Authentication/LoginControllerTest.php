<?php

namespace Tests\Feature;
use App\Models\Log;
use App\Models\User;
use Config;
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
    public function log_login_a_user()
    {
        Config::set('auth.providers.users.model', User::class);

        $user = factory(User::class)->create([
            'name' => 'Pepa Parda Jeans',
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
        $log = Log::latest()->first();
        $this->assertEquals($log->text,"L'usuari/a <strong>Pepa Parda Jeans</strong> ha entrat al sistema");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,$user->id);
        $this->assertEquals($log->action_type,'enter');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'input');
        $this->assertEquals($log->color,'teal');
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
    public function log_attempts()
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

    /**
     * @test
     */
    public function log_logout_user()
    {
        Config::set('auth.providers.users.model', User::class);
        $user = factory(User::class)->create([
            'name' => 'Pepa Parda Jeans',
            'email' => 'prova@gmail.com'
        ]);
        Auth::login($user);

        $this->assertNotNull(Auth::user());
        $this->assertTrue(Auth::user()->is($user));
        $this->post('/logout');
        $log = Log::latest()->first();
        $this->assertEquals($log->text,"L'usuari/a <strong>Pepa Parda Jeans</strong> ha sortit del sistema");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,$user->id);
        $this->assertEquals($log->action_type,'exit');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'input');
        $this->assertEquals($log->color,'purple');
    }
}
