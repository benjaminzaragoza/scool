<?php

namespace Tests\Unit\Tenants;

use App\Models\MoodleUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class MoodleUserTest.
 *
 * @package Tests\Unit
 */
class MoodleUserTest extends TestCase
{
    use RefreshDatabase;

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
    public function adapt()
    {
        $user = sample_moodle_user_array();
        $adaptedUser = MoodleUser::adapt($user);
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
        $this->assertCount(0,$user['errorMessages']);
        $this->assertFalse($user['inSync']);
        $this->assertCount(0,$user['flags']);
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
        $this->assertCount(0,$user['errorMessages']);
        $user = MoodleUser::userInSync($user, $scoolUser);
        $this->assertCount(1,$user['errorMessages']);
        $this->assertEquals(
            "Nom d'usuari Moodle incorrecte. S'ha trobat un usuari local amb Idnumber de Moodle però l'usuari de Moodle no correspon amb l'email corporatiu",
            $user['errorMessages']->first()
        );
        $this->assertFalse($user['inSync']);
        $this->assertEquals(MoodleUser::USERNAME_CAN_BE_SYNCED, $user['flags']->first());
    }

    /**
     * @test
     */
    public function userInSync()
    {
        $scoolUser = factory(User::class)->create();
        $user = sample_moodle_user_array($scoolUser->email);
        $user = MoodleUser::initializeUser($user);
        $this->assertTrue(array_key_exists('errorMessages',$user));
        $this->assertTrue(array_key_exists('inSync',$user));
        $this->assertTrue(array_key_exists('flags',$user));
        $this->assertCount(0,$user['errorMessages']);
        $user = MoodleUser::userInSync($user, $scoolUser);
        $this->assertCount(0,$user['errorMessages']);
        $this->assertCount(0,$user['flags']);
        $this->assertTrue($user['inSync']);
    }

    /**
     * @test
     */
    public function addLocalUser()
    {
        $scoolUser = null;
        $user = sample_moodle_user_array();
        $user = MoodleUser::initializeUser($user);
        $this->assertCount(0,$user['errorMessages']);
        $user = MoodleUser::addLocalUser($user,$scoolUser);
        $this->assertCount(1,$user['errorMessages']);
        $this->assertEquals('Idnumber no vàlid. No hi ha cap usuari local amb aquest id',$user['errorMessages'][0]);

        $scoolUser = factory(User::class)->create();
        $user = sample_moodle_user_array();
        $user = MoodleUser::addLocalUser($user,$scoolUser);
        $this->assertTrue($scoolUser->id === $user['localUser']['id']);
//        dd($user['localUser']);
//        if ($scoolUser) {
//            $user['localUser'] = $scoolUser->mapSimple();
//            $user = self::userInSync($user, $scoolUser);
//        } else {
//            $user['errorMessages'][] = 'Idnumber no vàlid. No hi ha cap usuari local amb aquest id';
//        }
//        return $user;
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
