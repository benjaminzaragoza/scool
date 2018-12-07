<?php

namespace Tests\Unit\Tenants;

use App\Models\MoodleUser;
use Tests\TestCase;

/**
 * Class MoodleUserTest.
 *
 * @package Tests\Unit
 */
class MoodleUserTest extends TestCase
{
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
