<?php

namespace Tests\Unit\Tenants;

use App\Models\GoogleUser;
use App\Models\MoodleUser;
use App\Models\User;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;

/**
 * Class MoodleUserTest.
 *
 * @package Tests\Unit
 */
class MoodleUserTest extends TestCase
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
     * @group moodle
     * @group slow
     */
    public function all()
    {
        $users = MoodleUser::all();
        $this->assertTrue(is_array($users));
    }

    /**
     * @test
     */
    public function adapt_by_employee_id()
    {
        $scoolUser = factory(User::class)->create();
        $scoolUser->assignGoogleUser(GoogleUser::create([
            'google_id' => 231312312,
            'google_email' => 'prova@email.com'
        ]));
        $user = sample_moodle_user_array('prova@email.com');
        $user = MoodleUser::initializeUser($user);
        $user->idnumber = $scoolUser->id;
        $this->assertFalse(array_key_exists('localUser', $user));
        $localUsers = User::all();
        $adaptedUser = MoodleUser::adapt($user, $localUsers);
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
        $user = sample_moodle_user_array('prova@email.com');
        $user = MoodleUser::initializeUser($user);
        $user->idnumber = null;
        $localUsers = User::all();
        $adaptedUser = MoodleUser::adapt($user, $localUsers);
        $this->assertCount(1, $adaptedUser->errorMessages);
        $this->assertEquals("Idnumber no vàlid. No hi ha cap usuari local amb aquest id", $adaptedUser->errorMessages->first());
        $this->assertCount(1, $adaptedUser->flags);
        $this->assertEquals(MoodleUser::ID_NUMBER_CAN_BE_SYNCED, $adaptedUser->flags[0]);
        $this->assertFalse($adaptedUser->inSync);
    }

    /**
     * @test
     */
    public function initializeUser()
    {
        $user = sample_moodle_user_array();
        $this->assertFalse(array_key_exists('errorMessages',$user));
        $this->assertFalse(array_key_exists('inSync',$user));
        $this->assertFalse(array_key_exists('flags',$user));
        $user = MoodleUser::initializeUser($user);
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
        $user = sample_moodle_user_array('emailinventat@myemail.com');
        $user = MoodleUser::initializeUser($user);
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = MoodleUser::userInSync($user, $scoolUser);
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
        $this->assertEquals(MoodleUser::USERNAME_CAN_BE_SYNCED, $user->flags[0]);
        $this->assertEquals(MoodleUser::ID_NUMBER_CAN_BE_SYNCED, $user->flags[1]);
    }

    /**
     * @test
     */
    public function userNotInSyncUsernameAndIdnumberIncorrect()
    {
        $scoolUser = factory(User::class)->create();
        $user = sample_moodle_user_array('emailinventat@myemail.com');
        $user = MoodleUser::initializeUser($user);
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = MoodleUser::userInSync($user, $scoolUser);
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
        $this->assertEquals(MoodleUser::USERNAME_CAN_BE_SYNCED, $user->flags->first());
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
        $user = sample_moodle_user_array('prova@email.com');
        $user = MoodleUser::initializeUser($user);
        $user->idnumber = 3244432;
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = MoodleUser::userInSync($user, $scoolUser);
        $this->assertCount(1,$user->errorMessages);
        $this->assertEquals(
            "Idnumber no vàlid. No hi ha cap usuari local amb aquest id",
            $user->errorMessages->first()
        );
        $this->assertFalse($user->inSync);
        $this->assertEquals(MoodleUser::ID_NUMBER_CAN_BE_SYNCED, $user->flags->first());
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
        $user = sample_moodle_user_array('prova@email.com');
        $user = MoodleUser::initializeUser($user);
        $user->idnumber = $scoolUser->id;
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user->errorMessages);
        $user = MoodleUser::userInSync($user, $scoolUser);
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
        $user = sample_moodle_user_array();
        $user = MoodleUser::initializeUser($user);
        $this->assertCount(0, $user->errorMessages);
        $user = MoodleUser::addLocalUser($user, $scoolUser);
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
        $user = sample_moodle_user_array('prova@email.com');
        $user = MoodleUser::initializeUser($user);
        $user->idnumber = $scoolUser->id;

        $user = MoodleUser::addLocalUser($user, $scoolUser);
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
        $user = sample_moodle_user_array();
        $user->idNumber = $scoolUser->id;
        $user = MoodleUser::initializeUser($user);

        $user = MoodleUser::addLocalUser($user,$scoolUser);
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
        $user = sample_moodle_user_array('prova@email.com');
        $user = MoodleUser::initializeUser($user);
        $user->idnumber = $scoolUser->id;
        $localUsers = User::all();
        $user = MoodleUser::addLocalUserByUsername($localUsers, $user);
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
        $user = sample_moodle_user_array('unexistingemail@mydomain.com');
        $user = MoodleUser::initializeUser($user);
        $user->idnumber = null;
        $localUsers = User::all();
        $user = MoodleUser::addLocalUserByUsername($localUsers, $user);
        $this->assertFalse($user->inSync);
        $this->assertCount(0,$user->errorMessages);
        $this->assertCount(0,$user->flags);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function create()
    {
        $user = [
            'username' => 'usuariesborrar18',
            'firstname' => 'usuari',
            'lastname' => 'esborrar',
            'email' => 'usuariesborrar18@gmail.com',
            'password' => '123456'
        ];
        MoodleUser::store($user);
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function canGet()
    {
        $user = MoodleUser::get('maninfo@iesebre.com');
        $this->assertFalse(is_array($user));
        $this->assertEquals($user->id, 2);
        $this->assertEquals($user->username, 'admin');
        $this->assertEquals($user->email, 'maninfo@iesebre.com');
    }

    /**
     * @test
     * @group moodle
     * @group slow
     */
    public function canDestroy()
    {
        $user = create_sample_moodle_user();
        MoodleUser::destroy($user->id);
        $result = MoodleUser::get($user->username);
        $this->assertNull($result);
    }

//    /**
//     * @test
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function send_password_change_email()
//    {
//      TODO -> not possible using Moodle webservice -> RESET SCOOL PASSWORD AND SET SAME PASSWORD TO MOODLE
//    }

    /**
     * Can change password.
     *
     * @test
     * @throws \Exception
     */
    public function can_change_password()
    {
        $user = create_sample_moodle_user();
        MoodleUser::change_password($user->id, 'topsecret');
        $this->assertTrue(true);
    }
}
