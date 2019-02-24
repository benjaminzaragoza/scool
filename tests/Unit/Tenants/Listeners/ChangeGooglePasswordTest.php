<?php

namespace Tests\Unit\Tenants\Listeners;

use App\Events\Users\Password\UserPasswordChangedByManager;
use App\Listeners\Users\Password\ChangeGooglePassword;
use App\Models\GoogleUser;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ChangeGooglePasswordTest.
 *
 * @package Tests\Unit
 */
class ChangeGooglePasswordTest extends TestCase
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
     * @group google
     * @group slow
     */
    public function change_google_password()
    {
        config_google_api();
        tune_google_client();
        $googleUser = create_sample_google_user();
        $listener = new ChangeGooglePassword();
        $user = factory(User::class)->create();
        GoogleUser::create([
            'user_id' => $user->id,
            'google_id' => $googleUser->id,
            'google_email' => $googleUser->primaryEmail,
        ]);
        $user = $user->fresh();
        $options= ['google' => true];
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));

        GoogleUser::destroy($googleUser->id);
        $this->assertTrue(true);
    }
}
