<?php

namespace Tests\Feature\Tenants\Moodle;

use App\Events\Moodle\MoodleUserAssociated;
use App\Events\Moodle\MoodleUserUnAssociated;
use App\Models\MoodleUser;
use App\Models\User;
use Event;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class UserMoodleControllerTest.
 *
 * @package Tests\Feature
 */
class UserMoodleControllerTest extends BaseTenantTest
{
    use RefreshDatabase,CanLogin;

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
    public function can_associate_moodle_user_to_user()
    {
        $this->loginAsUsersManager('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);
        Event::fake();
        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/moodle', [
            'moodle_id' => 89
        ]);

        $response->assertSuccessful();
        Event::assertDispatched(MoodleUserAssociated::class, function ($e) use ($user) {
            return $e->user->id === $user->id &&
                $e->moodleUser->moodle_id === 89;
        });

        $user = $user->fresh();
        $this->assertEquals($user->id, $user->moodleUser->user_id);
        $this->assertEquals(89, $user->moodleUser->moodle_id);
    }

    /** @test */
    public function can_associate_moodle_user_to_user_validation()
    {
        $this->loginAsUsersManager('api');


        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);

        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/moodle');

        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_cannot_associate_moodle_user_to_user()
    {
        $this->login('api');
        $user = factory(User::class)->create();
        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/moodle');
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_associate_moodle_user_to_user()
    {
        $user = factory(User::class)->create();
        $response = $this->json('POST', '/api/v1/user/' . $user->id . '/moodle');
        $response->assertStatus(401);
    }

    /** @test */
    public function can_unassociate_moodle_user_to_user()
    {
        $this->loginAsUsersManager('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);

        MoodleUser::create([
            'user_id' => $user->id,
            'moodle_id' => 89,
            'moodle_username' => $user->email
        ]);

        $this->assertEquals(89,$user->moodleUser->moodle_id);
        Event::fake();
        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/moodle');

        $response->assertSuccessful();
        Event::assertDispatched(MoodleUserUnAssociated::class, function ($e) use ($user) {
            return $e->user->id === $user->id &&
                intval($e->moodleUser) === 89;
        });
        $user = $user->fresh();
        $this->assertNull($user->moodleUser);
    }

    /** @test */
    public function regular_user_cannot_unassociate_moodle_user_to_user()
    {
        $this->login('api');

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);

        MoodleUser::create([
            'user_id' => $user->id,
            'moodle_id' => 89,
            'moodle_username' => $user->email
        ]);

        $this->assertEquals(89,$user->moodleUser->moodle_id);

        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/moodle');

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_user_cannot_unassociate_moodle_user_to_user()
    {
        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardojeans@gmail.com'
        ]);

        MoodleUser::create([
            'user_id' => $user->id,
            'moodle_id' => 89,
            'moodle_username' => $user->email
        ]);

        $this->assertEquals(89,$user->moodleUser->moodle_id);

        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/moodle');

        $response->assertStatus(401);
    }
}
