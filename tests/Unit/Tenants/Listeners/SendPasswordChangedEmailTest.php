<?php

namespace Tests\Unit\Tenants\Listeners;

use App\Events\Users\Password\UserPasswordChangedByManager;
use App\Listeners\Users\Password\ChangeGooglePassword;
use App\Listeners\Users\Password\SendPasswordChangedEmail;
use App\Mail\Users\Password\PasswordChanged;
use App\Models\GoogleUser;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Tests\TestCase;

/**
 * Class SendPasswordChangedEmailTest.
 *
 * @package Tests\Unit
 */
class SendPasswordChangedEmailTest extends TestCase
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
     */
    public function send_password_changed_email()
    {
        $listener = new SendPasswordChangedEmail();
        $user = factory(User::class)->create();
        $options= ['email' => true];
        Mail::fake();
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
        Mail::assertQueued(PasswordChanged::class, function ($mail) use ($user) {
            return $mail->user->id === $user->id && $mail->password === 'NEW_PASSWORD' ;
        });
    }

    /**
     * @test
     */
    public function password_changed_email_not_send()
    {
        $listener = new SendPasswordChangedEmail();
        $user = factory(User::class)->create();
        $options= [];
        Mail::fake();
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
        Mail::assertNothingSent();
        $options= ['email' => false];
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));
        Mail::assertNothingSent();
    }
}
