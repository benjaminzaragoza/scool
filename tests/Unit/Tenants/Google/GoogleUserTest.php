<?php

namespace Tests\Unit\Tenants;

use App\Models\GoogleUser;
use App\Models\User;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class GoogleUserTest.
 *
 * @package Tests\Unit
 */
class GoogleUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up.
     */
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
    public function findByEmployeeId()
    {
        $scoolUser = factory(User::class)->create();
        $localUsers = User::all();
        $googleUser = sample_google_user_array(231312312,$scoolUser->id,$scoolUser->email,'prova@iesebre.com');
        $result = GoogleUser::findByEmployeeId($localUsers,$googleUser);
        $this->assertNotNull($result);
        $this->assertEquals('App\Models\User', get_class($result));
        $this->assertTrue($result->is($scoolUser));
    }

    /**
     * @test
     */
    public function findByPrimaryEmail()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@iesebre.com'
        ]));
        $localUsers = User::all();
        $googleUser = sample_google_user_array(231312312,$scoolUser->id,$scoolUser->email,'prova@iesebre.com');
        $result = GoogleUser::findByPrimaryEmail($localUsers,$googleUser);
        $this->assertNotNull($result);
        $this->assertEquals('App\Models\User', get_class($result));
        $this->assertTrue($result->is($scoolUser));
    }

    /**
     * @test
     */
    public function findByPersonalEmail()
    {
        $scoolUser = factory(User::class)->create();
        $localUsers = User::all();
        $googleUser = sample_google_user_array(231312312,$scoolUser->id,$scoolUser->email,'prova@iesebre.com');
        $result = GoogleUser::findByPersonalEmail($localUsers,$googleUser);
        $this->assertNotNull($result);
        $this->assertEquals('App\Models\User', get_class($result));
        $this->assertTrue($result->is($scoolUser));
    }

    /**
     * @test
     */
    public function adapt()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@iesebre.com'
        ]));
        $user = sample_google_user_array(231312312,$scoolUser->id,$scoolUser->email,'prova@iesebre.com');
        $user = GoogleUser::initializeUser($user);
        $this->assertFalse(array_key_exists('localUser', $user));
        $localUsers = User::all();

        $adaptedUser = GoogleUser::adapt($user, $localUsers);

        $this->assertCount(0, $adaptedUser->errorMessages);
        $this->assertCount(0, $adaptedUser->flags);
        $this->assertTrue($adaptedUser->inSync);
        $this->assertNotNull($adaptedUser->localUser);
        $this->assertEquals($scoolUser->name, $adaptedUser->localUser['name']);
        $this->assertEquals($scoolUser->id, $adaptedUser->localUser['id']);
    }

    /**
     * @test
     */
    public function initializeUser()
    {
        $user = sample_google_user_array();
        $this->assertFalse(array_key_exists('errorMessages',$user));
        $this->assertFalse(array_key_exists('inSync',$user));
        $this->assertFalse(array_key_exists('flags',$user));
        $user = GoogleUser::initializeUser($user);
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $this->assertFalse($user->inSync);
        $this->assertCount(0,$user->flags);
    }

    /**
     * @test
     */
    public function userNotInSync()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@email.com'
        ]));
        $user = sample_google_user_array();
        $user = GoogleUser::initializeUser($user);
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = GoogleUser::userInSync($user, $scoolUser);
        $this->assertCount(3,$user->errorMessages);
        $this->assertEquals(
            'EmployeeId no vàlid. No hi ha cap usuari local amb aquest id',
            $user->errorMessages[0]
        );
        $this->assertEquals(
            'primaryEmail no vàlid. No hi ha cap usuari local amb aquest email corporatiu',
            $user->errorMessages[1]
        );
        $this->assertEquals(
            'personalEmail no vàlid. No hi ha cap usuari local amb aquest email personal',
            $user->errorMessages[2]
        );
        $this->assertFalse($user->inSync);
        $this->assertEquals(GoogleUser::EMPLOYEE_ID_NUMBER_CAN_BE_SYNCED, $user->flags[0]);
        $this->assertEquals(GoogleUser::PRIMARY_EMAIL_CAN_BE_SYNCED, $user->flags[1]);
        $this->assertEquals(GoogleUser::PERSONAL_EMAIL_CAN_BE_SYNCED, $user->flags[2]);
    }

    /**
     * @test
     */
    public function userInSync()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@email.com'
        ]));
        $user = sample_google_user_array(231312312, $scoolUser->id, $scoolUser->email , 'prova@email.com'  );

        $user = GoogleUser::initializeUser($user);
        $user->idnumber = $scoolUser->id;
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = GoogleUser::userInSync($user, $scoolUser);
        $this->assertCount(0,$user->errorMessages);
        $this->assertCount(0,$user->flags);
        $this->assertTrue($user->inSync);
    }

    /**
     * @test
     */
    public function addLocalUserError()
    {
        $scoolUser = null;
        $user = sample_google_user_array();
        $user = GoogleUser::initializeUser($user);
        $this->assertCount(0, $user->errorMessages);
        $user = GoogleUser::addLocalUser($user, $scoolUser);
        $this->assertCount(3, $user->errorMessages);
        $this->assertEquals(
            'EmployeeId no vàlid. No hi ha cap usuari local amb aquest id',
            $user->errorMessages[0]
        );
        $this->assertEquals(
            'primaryEmail no vàlid. No hi ha cap usuari local amb aquest email corporatiu',
            $user->errorMessages[1]
        );
        $this->assertEquals(
            'personalEmail no vàlid. No hi ha cap usuari local amb aquest email personal',
            $user->errorMessages[2]
        );
        $this->assertFalse($user->inSync);
        $this->assertEquals(GoogleUser::EMPLOYEE_ID_NUMBER_CAN_BE_SYNCED, $user->flags[0]);
        $this->assertEquals(GoogleUser::PRIMARY_EMAIL_CAN_BE_SYNCED, $user->flags[1]);
        $this->assertEquals(GoogleUser::PERSONAL_EMAIL_CAN_BE_SYNCED, $user->flags[2]);
    }

    /**
     * @test
     */
    public function addLocalUser()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@email.com'
        ]));
        $user = sample_google_user_array(231312312, $scoolUser->id, $scoolUser->email, 'prova@email.com');
        $user = GoogleUser::initializeUser($user);
        $user->idnumber = $scoolUser->id;

        $user = GoogleUser::addLocalUser($user, $scoolUser);
        $this->assertTrue($scoolUser->id === $user->localUser['id']);
        $this->assertTrue($user->inSync);
        $this->assertCount(0,$user->errorMessages);
        $this->assertCount(0,$user->flags);
    }

    /**
     * @test
     */
    public function addLocalUserButNotSync()
    {
        $scoolUser = factory(User::class)->create();
        $user = sample_google_user_array();
        $user = GoogleUser::initializeUser($user);

        $user = GoogleUser::addLocalUser($user,$scoolUser);
        $this->assertTrue($scoolUser->id === $user->localUser['id']);
        $this->assertCount(3,$user->errorMessages);
        $this->assertEquals(
            'EmployeeId no vàlid. No hi ha cap usuari local amb aquest id',
            $user->errorMessages[0]
        );
        $this->assertEquals(
            'primaryEmail no vàlid. No hi ha cap usuari local amb aquest email corporatiu',
            $user->errorMessages[1]
        );
        $this->assertEquals(
            'personalEmail no vàlid. No hi ha cap usuari local amb aquest email personal',
            $user->errorMessages[2]
        );
        $this->assertFalse($user->inSync);
        $this->assertEquals(GoogleUser::EMPLOYEE_ID_NUMBER_CAN_BE_SYNCED, $user->flags[0]);
        $this->assertEquals(GoogleUser::PRIMARY_EMAIL_CAN_BE_SYNCED, $user->flags[1]);
        $this->assertEquals(GoogleUser::PERSONAL_EMAIL_CAN_BE_SYNCED, $user->flags[2]);
    }

}
