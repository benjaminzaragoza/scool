<?php

namespace Tests\Feature;
use App\Models\Log;
use App\Models\User;
use Auth;
use Config;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class ImpersonateTest.
 *
 * @package Tests\Feature
 */
class ImpersonateTest extends BaseTenantTest
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
    public function log_take_impersonate()
    {
        Config::set('auth.providers.users.model', User::class);

        $user = factory(User::class)->create([
            'name' => 'Pepa Parda Jeans',
            'email' => 'prova@gmail.com',
            'admin' => true
        ]);
        $other_user = factory(User::class)->create([
            'name' => 'Pepa Pig',
            'email' => 'pepapig@gmail.com'
        ]);
        Auth::login($user);
        Log::truncate();
        Auth::user()->impersonate($other_user);
        $log = Log::first();
        $this->assertEquals($log->text,"L'usuari/a admin <strong>Pepa Parda Jeans - prova@gmail.com</strong> s'esta fent passar per <strong>Pepa Pig - pepapig@gmail.com</strong>");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,$user->id);
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
    public function log_leave_impersonation()
    {
        Config::set('auth.providers.users.model', User::class);

        $user = factory(User::class)->create([
            'name' => 'Pepa Parda Jeans',
            'email' => 'prova@gmail.com'
        ]);
        $other_user = factory(User::class)->create([
            'name' => 'Pepa Pig',
            'email' => 'pepapig@gmail.com'
        ]);
        Auth::login($user);
        Auth::user()->impersonate($other_user);

        Log::truncate();
        Auth::user()->leaveImpersonation();
        $log = Log::first();
        $this->assertEquals($log->text,"L'usuari/a admin <strong>Pepa Parda Jeans - prova@gmail.com</strong> ha deixat de fer-se passar per <strong>Pepa Pig - pepapig@gmail.com</strong>");
        $this->assertNotNull($log->time);
        $this->assertEquals($log->user_id,$user->id);
        $this->assertEquals($log->action_type,'update');
        $this->assertEquals($log->module_type,'UsersManagment');
        $this->assertEquals($log->loggable_id,1);
        $this->assertEquals($log->loggable_type,'App\Models\User');
        $this->assertNotNull($log->persistedLoggable);
        $this->assertEquals($log->icon,'edit');
        $this->assertEquals($log->color,'teal');
    }
}
