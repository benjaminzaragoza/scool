<?php

namespace Tests\Unit\Tenants\Listeners;

use App\Events\Users\Password\UserPasswordChangedByManager;
use App\Listeners\Users\Password\ChangeMoodlePassword;
use App\Models\MoodleUser;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ChangeMoodlePasswordTest.
 *
 * @package Tests\Unit
 */
class ChangeMoodlePasswordTest extends TestCase
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
     * @group moodle
     * @group slow
     */
    public function change_moodle_password()
    {
        $moodleUser = create_sample_moodle_user();
        $listener = new ChangeMoodlePassword();
        $user = factory(User::class)->create();
        MoodleUser::create([
            'user_id' => $user->id,
            'moodle_id' => $moodleUser->id,
            'moodle_username' => $moodleUser->username,
        ]);
        $user = $user->fresh();
        $options= ['moodle' => true];
        $listener->handle(new UserPasswordChangedByManager($user,'NEW_PASSWORD', $options));

        MoodleUser::destroy($moodleUser->id);
        $this->assertTrue(true);
    }

}
