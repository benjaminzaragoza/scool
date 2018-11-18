<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Log;
use Config;
use Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class RegisterControllerTest
 * @package Tests\Feature
 */
class RegisterControllerTest extends BaseTenantTest
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

    /** @test */
    public function can_register_a_user()
    {
        $this->withoutExceptionHandling();
        Config::set('auth.providers.users.model', User::class);
        $this->assertNull(Auth::user());

        Event::fake();
        // Execution
        $response = $this->post('/register', $user = [
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        Event::assertDispatched(Registered::class, function ($e) {
            return $e->user->name === 'Pepe Pardo Jeans' && $e->user->email === 'pepepardo@jeans.com';
        });

        $response->assertStatus(302);
        $response->assertRedirect('/home');

        // Comprovat s'ha creat el usuari
        $this->assertEquals($user['email'],Auth::user()->email);
        $this->assertEquals($user['name'],Auth::user()->name);
        $this->assertNotNull(Auth::user());
        $this->assertTrue(Hash::check($user['password'],Auth::user()->password));
    }

    /** @test */
    public function a_log_is_created_when_a_user_is_registered()
    {
        // Execution
        $this->assertNull(Log::latest()->first());
        Config::set('auth.providers.users.model', User::class);
        Log::truncate();
        $this->post('/register', $user = [
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        $this->assertNotNull(Log::latest()->first());
        $log = Log::first();
        $this->assertEquals($log->text,'Usuari/a <strong>Pepe Pardo Jeans</strong> registrat amb l\'email <strong> pepepardo@jeans.com</strong>');
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,1);
        $this->assertEquals($log->action_type,'store');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'input');
        $this->assertEquals($log->color,'success');
    }
}
