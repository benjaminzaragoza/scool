<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use App\Notifications\VerifyEmail;
use App\Notifications\WelcomeEmailNotification;
use Config;
use Notification;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class SendWelcomeEmailVerificationTest.
 *
 * @package Tests\Feature
 */
class SendWelcomeEmailVerificationTest extends BaseTenantTest
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
    public function user_manager_can_send_welcome_email()
    {
        $this->withoutExceptionHandling();
        Notification::fake();
        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $user = create(User::class);

        $response = $this->json('GET','/api/v1/email/welcome/' . $user->id);

        $response->assertSuccessful();

        Notification::assertSentTo($user,WelcomeEmailNotification::class);
    }

    /** @test */
    public function user_cannot_send_welcome_email()
    {
        $regularUser = create(User::class);
        $this->actingAs($regularUser,'api');
        Config::set('auth.providers.users.model', User::class);

        $user = create(User::class);

        $response = $this->json('GET','/api/v1/email/welcome/' . $user->id);

        $response->assertStatus(403);
    }
}
