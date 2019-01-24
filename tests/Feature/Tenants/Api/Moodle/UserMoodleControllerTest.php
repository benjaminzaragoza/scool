<?php

namespace Tests\Feature\Tenants;

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
        Event::assertDispatched(MoodleUserAssociated::class, function ($e) use ($user) {
            return $e->user->id === $user->id &&
                   $e->moodleUser->moodle_id === 89;
        });
        $response->assertSuccessful();

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
            'moodle_id' => 89
        ]);

        $this->assertEquals(89,$user->moodleUser->moodle_id);
        Event::fake();
        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/moodle');
        Event::assertDispatched(MoodleUserUnAssociated::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
        $response->assertSuccessful();

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
            'moodle_id' => 89
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
            'moodle_id' => 89
        ]);

        $this->assertEquals(89,$user->moodleUser->moodle_id);

        $response = $this->json('DELETE', '/api/v1/user/' . $user->id . '/moodle');

        $response->assertStatus(401);
    }
//
//    /**
//     * @test
//     * @group google
//     */
//    public function sync_user_to_moodle_user()
//    {
//        config_google_api();
//        tune_google_client();
//        $manager = factory(User::class)->create();
//        $role = Role::firstOrCreate(['name' => 'UsersManager']);
//        Config::set('auth.providers.users.model', User::class);
//        $manager->assignRole($role);
//        $this->actingAs($manager, 'api');
//
//        $user = factory(User::class)->create([
//            'name' => 'Pepe Pardo Jeans',
//            'email' => 'pepepardojeans@gmail.com',
//            'mobile' => '654789524'
//        ]);
//
//        MoodleUser::create([
//            'user_id' => $user->id,
//            'moodle_id' => 7896454538713789,
//            'google_email' => 'pepepardojeans@iesebre.com',
//        ]);
//
//        $response = $this->json('PUT', '/api/v1/user/' . $user->id . '/moodle');
//
//        $response->assertSuccessful();
//        $result = json_decode($response->getContent());
//
//        $this->assertEquals('pepepardojeans@gmail.com',$result->emails[0]->address);
//        $this->assertEquals('654789524',$result->phones[0]->value);
//        $this->assertEquals($user->id,$result->externalIds[0]->value);
//    }

//    /**
//     * @test
//     * @group google
//     */
//    public function sync_user_to_moodle_user_throws_422error_without_valid_google_user_associated()
//    {
//        $manager = factory(User::class)->create();
//        $role = Role::firstOrCreate(['name' => 'UsersManager']);
//        Config::set('auth.providers.users.model', User::class);
//        $manager->assignRole($role);
//        $this->actingAs($manager, 'api');
//
//        $user = factory(User::class)->create([
//            'name' => 'Pepe Pardo Jeans',
//            'email' => 'pepepardojeans@gmail.com',
//            'mobile' => '654789524'
//        ]);
//
//        MoodleUser::create([
//            'user_id' => $user->id,
//            'moodle_id' => 7896454538713789,
//            'google_email' => 'dsadasdasasdasdasddasasdasdadsasdasdasdasd@iesebre.com',
//        ]);
//
//        $response = $this->json('PUT', '/api/v1/user/' . $user->id . '/moodle');
//
//        $response->assertStatus(422);
//        $this->assertEquals('No existeix el compte de Google dsadasdasasdasdasddasasdasdadsasdasdasdasd@iesebre.com', json_decode($response->getContent())->message);
//    }

//    /**
//     * @test
//     * @google
//     */
//    public function sync_user_to_moodle_user_throws_422error_without_google_user_associated()
//    {
//        $manager = factory(User::class)->create();
//        $role = Role::firstOrCreate(['name' => 'UsersManager']);
//        Config::set('auth.providers.users.model', User::class);
//        $manager->assignRole($role);
//        $this->actingAs($manager, 'api');
//
//        $user = factory(User::class)->create([
//            'name' => 'Pepe Pardo Jeans',
//            'email' => 'pepepardojeans@gmail.com',
//            'mobile' => '654789524'
//        ]);
//
//        $response = $this->json('PUT', '/api/v1/user/' . $user->id . '/moodle');
//
//        $response->assertStatus(422);
//        $this->assertEquals("L'usuari Pepe Pardo Jeans no té un compte de Google associat", json_decode($response->getContent())->message);
//    }

//    /**
//     * @test
//     */
//    public function regular_user_cannot_sync_user_to_moodle_user()
//    {
//        $regularUser = factory(User::class)->create();
//        $this->actingAs($regularUser, 'api');
//
//        $user = factory(User::class)->create([
//            'name' => 'Pepe Pardo Jeans',
//            'email' => 'pepepardojeans@gmail.com',
//            'mobile' => '654789524'
//        ]);
//
//        $response = $this->json('PUT', '/api/v1/user/' . $user->id . '/moodle');
//
//        $response->assertStatus(403);
//    }
}
