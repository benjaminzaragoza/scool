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
    public function adapt()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@email.com'
        ]));
        $user = sample_google_user_array(231312312);
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
    public function adaptByUsername()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@email.com'
        ]));
        $user = sample_google_user_array('prova@email.com');
        $user = GoogleUser::initializeUser($user);
        $user->idnumber = null;
        $localUsers = User::all();
        $adaptedUser = GoogleUser::adapt($user, $localUsers);
        $this->assertCount(1, $adaptedUser->errorMessages);
        $this->assertEquals("Idnumber no vàlid. No hi ha cap usuari local amb aquest id", $adaptedUser->errorMessages->first());
        $this->assertCount(1, $adaptedUser->flags);
        $this->assertEquals(GoogleUser::ID_NUMBER_CAN_BE_SYNCED, $adaptedUser->flags[0]);
        $this->assertFalse($adaptedUser->inSync);
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
        $user = sample_google_user_array('emailinventat@myemail.com');
        $user = GoogleUser::initializeUser($user);
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = GoogleUser::userInSync($user, $scoolUser);
        $this->assertCount(2,$user->errorMessages);
        $this->assertEquals(
            "Nom d'usuari Moodle incorrecte. S'ha trobat un usuari local amb Idnumber de Moodle però l'usuari de Moodle no correspon amb l'email corporatiu",
            $user->errorMessages[0]
        );
        $this->assertEquals(
            "Idnumber no vàlid. No hi ha cap usuari local amb aquest id",
            $user->errorMessages[1]
        );
        $this->assertFalse($user->inSync);
        $this->assertEquals(GoogleUser::USERNAME_CAN_BE_SYNCED, $user->flags[0]);
        $this->assertEquals(GoogleUser::ID_NUMBER_CAN_BE_SYNCED, $user->flags[1]);
    }

    /**
     * @test
     */
    public function userNotInSyncUsernameAndIdnumberIncorrect()
    {
        $scoolUser = factory(User::class)->create();
        $user = sample_google_user_array('emailinventat@myemail.com');
        $user = GoogleUser::initializeUser($user);
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = GoogleUser::userInSync($user, $scoolUser);
        $this->assertCount(2,$user->errorMessages);
        $this->assertEquals(
            "Nom d'usuari Moodle incorrecte. S'ha trobat un usuari local amb Idnumber de Moodle però l'usuari de Moodle no correspon amb l'email corporatiu",
            $user->errorMessages[0]
        );
        $this->assertEquals(
            'Idnumber no vàlid. No hi ha cap usuari local amb aquest id',
            $user->errorMessages[1]
        );
        $this->assertFalse($user->inSync);
        $this->assertEquals(GoogleUser::USERNAME_CAN_BE_SYNCED, $user->flags->first());
    }

    /**
     * @test
     */
    public function userNotInSyncIdnumberIncorrect()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@email.com'
        ]));
        $user = sample_google_user_array('prova@email.com');
        $user = GoogleUser::initializeUser($user);
        $user->idnumber = 3244432;
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = GoogleUser::userInSync($user, $scoolUser);
        $this->assertCount(1,$user->errorMessages);
        $this->assertEquals(
            "Idnumber no vàlid. No hi ha cap usuari local amb aquest id",
            $user->errorMessages->first()
        );
        $this->assertFalse($user->inSync);
        $this->assertEquals(GoogleUser::ID_NUMBER_CAN_BE_SYNCED, $user->flags->first());
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
        $user = sample_google_user_array('prova@email.com');
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
        $this->assertCount(1, $user->errorMessages);
        $this->assertEquals('Idnumber no vàlid. No hi ha cap usuari local amb aquest id', $user->errorMessages[0]);
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
        $user = sample_google_user_array('prova@email.com');
        $user = GoogleUser::initializeUser($user);
        $user->idnumber = $scoolUser->id;

        $user = GoogleUser::addLocalUser($user, $scoolUser);
        $this->assertTrue($scoolUser->id === $user->localUser['id']);
        $this->assertTrue($user->inSync);
        $this->assertCount(0,$user->errorMessages);
    }

    /**
     * @test
     */
    public function addLocalUserButNotSync()
    {
        $scoolUser = factory(User::class)->create();
        $user = sample_google_user_array();
        $user->idNumber = $scoolUser->id;
        $user = GoogleUser::initializeUser($user);

        $user = GoogleUser::addLocalUser($user,$scoolUser);
        $this->assertTrue($scoolUser->id === $user->localUser['id']);
        $this->assertFalse($user->inSync);
        $this->assertEquals(
            "Nom d'usuari Moodle incorrecte. S'ha trobat un usuari local amb Idnumber de Moodle però l'usuari de Moodle no correspon amb l'email corporatiu",
            $user->errorMessages->first());
    }

    /**
     * @test
     */
    public function addLocalUserByUsername()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@email.com'
        ]));
        $user = sample_google_user_array('prova@email.com');
        $user = GoogleUser::initializeUser($user);
        $user->idnumber = $scoolUser->id;
        $localUsers = User::all();
        $user = GoogleUser::addLocalUserByUsername($localUsers, $user);
        $this->assertTrue($scoolUser->id === $user->localUser['id']);
        $this->assertTrue($user->inSync);
        $this->assertCount(0,$user->errorMessages);
        $this->assertCount(0,$user->flags);
        $this->assertNotNull($user->localUser);
        $this->assertEquals($scoolUser->name, $user->localUser['name']);
        $this->assertEquals($scoolUser->id, $user->localUser['id']);
    }

    /**
     * @test
     */
    public function addLocalUserByUnexistingUsername()
    {
        $user = sample_google_user_array('unexistingemail@mydomain.com');
        $user = GoogleUser::initializeUser($user);
        $user->idnumber = null;
        $localUsers = User::all();
        $user = GoogleUser::addLocalUserByUsername($localUsers, $user);
        $this->assertFalse($user->inSync);
        $this->assertCount(0,$user->errorMessages);
        $this->assertCount(0,$user->flags);
    }

}
