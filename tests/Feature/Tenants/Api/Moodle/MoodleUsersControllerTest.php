<?php

namespace Tests\Feature\Tenants\Api\Incidents;

use App\Events\Moodle\MoodleUserCreated;
use App\Models\User;
use App\Models\MoodleUser;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;
use Illuminate\Contracts\Console\Kernel;
use Tests\Feature\Tenants\Traits\CanLogin;

/**
 * Class MoodleUsersControllerTest.
 *
 * @package Tests\Feature\Tenants\Api
 */
class MoodleUsersControllerTest extends BaseTenantTest {

    use RefreshDatabase, CanLogin;

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
    public function superadmin_can_list_moodle_users()
    {
        $this->loginAsSuperAdmin('api');
        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertTrue(is_array($result));
        $this->assertNotNull($result[0]->id);
        $this->assertNotNull($result[0]->username);
        $this->assertNotNull($result[0]->email);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function manager_can_list_moodle_users()
    {
        $this->loginAsMoodleManager('api');
        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertSuccessful();
        $result = json_decode($response->getContent());
        $this->assertTrue(is_array($result));
        $this->assertNotNull($result[0]->id);
        $this->assertNotNull($result[0]->username);
        $this->assertNotNull($result[0]->email);
    }

    /**
     * @test
     * @group moodle
     *
     */
    public function regular_user_cannot_list_moodle_users()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group moodle
     *
     */
    public function guest_user_cannot_list_moodle_users()
    {
        $response =  $this->json('GET','/api/v1/moodle/users');
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function superadmin_can_create_moodle_users()
    {
        $this->loginAsSuperAdmin('api');
        $moddleUser = MoodleUser::get('usuariesborrar18@gmail.com');
        if ($moddleUser) MoodleUser::destroy($moddleUser->id);
        $params = [
            'user' => [
                'username' => 'usuariesborrar18',
                'firstname' => 'usuari',
                'lastname' => 'esborrar',
                'email' => 'usuariesborrar18@gmail.com',
                'password' => '123456'
           ]
        ];
        Event::fake();
        $response =  $this->json('POST','/api/v1/moodle/users', $params);
        $response->assertSuccessful();
        Event::assertDispatched(MoodleUserCreated::class, function ($e) use ($params) {
            return $e->moodleUser->username === $params['user']['username'];
        });
        $result = json_decode($response->getContent());
        $moddleUser = MoodleUser::get('usuariesborrar18@gmail.com');
        $this->assertNotNull($moddleUser);
        $this->assertEquals($result->username,'usuariesborrar18');
        if ($moddleUser) MoodleUser::destroy($moddleUser->id);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function manager_can_create_moodle_users()
    {
        $this->loginAsMoodleManager('api');
        $moddleUser = MoodleUser::get('usuariesborrar18@gmail.com');
        if ($moddleUser) MoodleUser::destroy($moddleUser->id);
        $params = [
            'user' => [
                'username' => 'usuariesborrar18',
                'firstname' => 'usuari',
                'lastname' => 'esborrar',
                'email' => 'usuariesborrar18@gmail.com',
                'password' => '123456'
            ]
        ];
        Event::fake();
        $response =  $this->json('POST','/api/v1/moodle/users', $params);
        $response->assertSuccessful();
        Event::assertDispatched(MoodleUserCreated::class, function ($e) use ($params) {
            return $e->moodleUser->username === $params['user']['username'];
        });
        $result = json_decode($response->getContent());
        $moddleUser = MoodleUser::get('usuariesborrar18@gmail.com');
        $this->assertNotNull($moddleUser);
        $this->assertEquals($result->username,'usuariesborrar18');
        if ($moddleUser) MoodleUser::destroy($moddleUser->id);
    }

    /**
     * @test
     * @group moodle
     *
     */
    public function regular_user_cannot_create_moodle_users()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $user = [
            'username' => 'usuariesborrar18',
            'firstname' => 'usuari',
            'lastname' => 'esborrar',
            'email' => 'usuariesborrar18@gmail.com',
            'password' => '123456'
        ];
        $response =  $this->json('POST','/api/v1/moodle/users', $user);
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group moodle
     *
     */
    public function guest_user_cannot_create_moodle_users()
    {
        $user = [
            'username' => 'usuariesborrar18',
            'firstname' => 'usuari',
            'lastname' => 'esborrar',
            'email' => 'usuariesborrar18@gmail.com',
            'password' => '123456'
        ];
        $response =  $this->json('POST','/api/v1/moodle/users', $user);
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group moodle
     *
     */
    public function superadmin_can_destroy_moodle_users()
    {
        $this->loginAsSuperAdmin('api');
        $user = create_sample_moodle_user();
        $response =  $this->json('DELETE','/api/v1/moodle/users/' . $user->id);
        $response->assertSuccessful();
        $this->assertNull(MoodleUser::get($user->username));
    }

    /**
     * @test
     * @group moodle
     *
     */
    public function regular_user_cannot_destroy_moodle_users()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');
        $response =  $this->json('DELETE','/api/v1/moodle/users/1');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group moodle
     *
     */
    public function guest_user_cannot_destroy_moodle_users()
    {
        $response =  $this->json('DELETE','/api/v1/moodle/users/1');
        $response->assertStatus(401);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function manager_can_delete_multiple_users()
    {
        $this->loginAsMoodleManager('api');
        $user1 = MoodleUser::get('usuariesborrar@gmail.com');
        if ($user1) MoodleUser::destroy($user1->id);
        $user2 = MoodleUser::get('usuari2esborrar2@gmail.com');
        if ($user2) MoodleUser::destroy($user2->id);
        $user1 = create_sample_moodle_user();
        $user2 = create_sample_moodle_user( [
            'username' => 'usuariesborrar2',
            'firstname' => 'usuari2',
            'lastname' => 'esborrar2',
            'email' => 'usuari2esborrar2@gmail.com',
            'password' => '123456'
        ]);

        $response = $this->json('POST','/api/v1/moodle/users/multiple', [
            'users' => [ $user1->id, $user2->id ]
        ]);

        $response->assertSuccessful();
        $this->assertNull(MoodleUser::get($user1->username));
        $this->assertNull(MoodleUser::get($user2->username));
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function manager_can_delete_multiple_users_validation()
    {
        $this->loginAsMoodleManager('api');


        $response = $this->json('POST','/api/v1/moodle/users/multiple');

        $response->assertStatus(422);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function regular_user_cannot_delete_multiple_users_validation()
    {
        $this->login('api');
        $response = $this->json('POST','/api/v1/moodle/users/multiple');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function guest_user_cannot_delete_multiple_users_validation()
    {
        $response = $this->json('POST','/api/v1/moodle/users/multiple');
        $response->assertStatus(401);
    }

}
