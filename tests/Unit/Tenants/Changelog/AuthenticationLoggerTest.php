<?php

namespace Tests\Unit\Tenants\Changelog;

use App\Models\User;
use App\Console\Kernel;
use App\Listeners\Authentication\AuthenticationLogger;
use App\Models\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class AuthenticationLoggerTest.
 *
 * @package Tests\Unit\Tenants
 */
class AuthenticationLoggerTest extends TestCase
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
    public function incorrectAttempt()
    {
        $event = (Object) [
            'credentials' => [
                'email' => 'prova@gmail.com'
            ]
        ];
        AuthenticationLogger::incorrectAttempt($event);
        $log = Log::first();
        $this->assertEquals($log->text,"Intent de login incorrecte amb l'usuari <strong>prova@gmail.com</strong>");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id, null);
        $this->assertEquals($log->action_type,'error');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,null);
        $this->assertEquals($log->loggable_type,null);
        $this->assertNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'error');
        $this->assertEquals($log->color,'error');
    }

    /**
     * @test
     */
    public function logout()
    {
        $event = (Object) [
            'user' => factory(User::class)->create([
                'name' => 'Pepa Parda Jeans'
            ])
        ];
        AuthenticationLogger::logout($event);
        $log = Log::first();
        $this->assertEquals($log->text,"L'usuari/a <strong>Pepa Parda Jeans</strong> ha sortit del sistema");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,$event->user->id);
        $this->assertEquals($log->action_type,'exit');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'exit_to_app');
        $this->assertEquals($log->color,'purple');
    }

    /**
     * @test
     */
    public function leaveImpersonation()
    {
        $event = (Object) [
            'impersonator' => factory(User::class)->create([
                'id' => 1,
                'name' => 'Pepa Parda Jeans',
                'email' => 'pepaparda@jeans.com'
            ]),
            'impersonated' => factory(User::class)->create([
                'name' => 'Pepa Pig',
                'email' => 'pepapig@gmail.com'
            ])
        ];
        AuthenticationLogger::leaveImpersonation($event);
        $log = Log::first();

        $this->assertEquals($log->text,"L'usuari/a admin <strong>Pepa Parda Jeans - pepaparda@jeans.com</strong> ha deixat de fer-se passar per <strong>Pepa Pig - pepapig@gmail.com</strong>");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,1);
        $this->assertEquals($log->action_type,'update');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'edit');
        $this->assertEquals($log->color,'teal');
    }

    /**
     * @test
     */
    public function takeImpersonation()
    {
        $event = (Object) [
            'impersonator' => factory(User::class)->create([
                'id' => 1,
                'name' => 'Pepa Parda Jeans',
                'email' => 'prova@gmail.com'
            ]),
            'impersonated' => factory(User::class)->create([
                'name' => 'Pepa Pig',
                'email' => 'pepapig@gmail.com'
            ])
        ];
        AuthenticationLogger::takeImpersonation($event);
        $log = Log::first();

        $this->assertEquals($log->text,"L'usuari/a admin <strong>Pepa Parda Jeans - prova@gmail.com</strong> s'esta fent passar per <strong>Pepa Pig - pepapig@gmail.com</strong>");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,1);
        $this->assertEquals($log->action_type,'update');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'edit');
        $this->assertEquals($log->color,'teal');
    }

    /**
     * @test
     */
    public function login()
    {
        $event = (Object) [
            'user' => factory(User::class)->create([
                'id' => 1,
                'name' => 'Pepa Parda Jeans',
                'email' => 'prova@gmail.com',
                'last_login_ip' => '147.26.35.38'
            ])
        ];
        AuthenticationLogger::login($event);
        $log = Log::first();
        $this->assertEquals($log->text,"L'usuari/a <a target=\"_blank\" href=\"/users/1\">Pepa Parda Jeans</a> (prova@gmail.com) ha entrat al sistema des de la IP: 147.26.35.38");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,1);
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
    public function passwordReset()
    {
        $event = (Object) [
            'user' => factory(User::class)->create([
                'id' => 1,
                'name' => 'Pepa Parda Jeans',
                'email' => 'prova@gmail.com'
            ])
        ];
        AuthenticationLogger::passwordReset($event);
        $log = Log::first();
        $this->assertEquals($log->text,"L'usuari/a <strong>Pepa Parda Jeans</strong> ha modificat la paraula de pas");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,1);
        $this->assertEquals($log->action_type,'update');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'edit');
        $this->assertEquals($log->color,'teal');
    }

    /**
     * @test
     */
    public function registered()
    {
        $event = (Object) [
            'user' => factory(User::class)->create([
                'id' => 1,
                'name' => 'Pepe Pardo Jeans',
                'email' => 'pepepardo@jeans.com'
            ])
        ];
        AuthenticationLogger::registered($event);

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

    /**
     * @test
     */
    public function verifiedUser()
    {
        $event = (Object) [
            'user' => factory(User::class)->create([
                'id' => 1,
                'name' => 'Pepa Parda Jeans',
                'email' => 'prova@gmail.com'
            ])
        ];
        AuthenticationLogger::verifiedUser($event);
        $log = Log::first();
        $this->assertEquals($log->text,"L'usuari/a <strong>Pepa Parda Jeans</strong> ha verificat l'email <strong>prova@gmail.com</strong>");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,1);
        $this->assertEquals($log->action_type,'update');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'edit');
        $this->assertEquals($log->color,'teal');

    }
}
