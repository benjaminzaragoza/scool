<?php

namespace Tests\Feature\Tenants;

use App;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Notification;
use Tests\BaseTenantTest;

/**
 * Class ForgotPasswordControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class ForgotPasswordControllerTest extends BaseTenantTest
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
    public function users_can_use_personal_email_to_reset_password()
    {
        Notification::fake();
        Config::set('auth.providers.users.model', User::class);
        Config::set('auth.providers.users.driver', 'scool');
        $user = factory(User::class)->create([
            'email' => 'acacha@acacha.gmail.com'
        ]);

        $user->assignPersonalData([
            'email' => 'acacha@xtec.cat'
        ]);

        $response = $this->json('POST','/password/email', [
            'email' => 'acacha@xtec.cat'
        ]);
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertEquals($result->status,"T'hem enviat per e-mail un enllaç per a reiniciar la teva contrasenya!");
        $response->assertSuccessful();

        Notification::assertSentTo(
            [$user], ResetPasswordNotification::class
        );
    }

    /** @test */
    public function users_can_use_user_email_to_reset_password()
    {
        Notification::fake();
        Config::set('auth.providers.users.model', User::class);

        $user = factory(User::class)->create([
            'email' => 'acacha@acacha.gmail.com'
        ]);
        $response = $this->json('POST','/password/email', [
            'email' => $user->email
        ]);
        $result = json_decode($response->getContent());
        $this->assertEquals($result->status,"T'hem enviat per e-mail un enllaç per a reiniciar la teva contrasenya!");
        $response->assertSuccessful();

        Notification::assertSentTo(
            [$user], ResetPasswordNotification::class
        );
    }

    /** @test */
    public function users_can_use_personal_email_to_reset_password_validation()
    {
        $response = $this->json('POST','/password/email', [
            'email' => 'personalemail@gmail.com'
        ]);
        $result = json_decode($response->getContent());
        $response->assertStatus(422);
        $this->assertEquals($result->message,'The given data was invalid.');
        $this->assertInstanceOf('StdClass',$result->errors);
        $this->assertEquals($result->errors->email,'No existeix cap usuari amb aquest correu.');
    }
}
