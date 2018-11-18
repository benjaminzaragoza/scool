<?php

namespace Tests\Feature;
use App\Models\Log;
use App\Models\User;
use Config;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class PasswordResetControllerTest
 * @package Tests\Feature
 */
class PasswordResetControllerTest extends BaseTenantTest
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
    public function log_password_reset()
    {
        Config::set('auth.providers.users.model', User::class);

        $user = factory(User::class)->create([
            'name' => 'Pepa Parda Jeans',
            'email' => 'prova@gmail.com'
        ]);
        Log::truncate();
        event(new PasswordReset($user));
        $log = Log::first();
        $this->assertEquals($log->text,"L'usuari/a <strong>Pepa Parda Jeans</strong> ha modificat la paraula de pas");
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
