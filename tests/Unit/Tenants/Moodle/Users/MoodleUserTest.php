<?php

namespace Tests\Unit\Tenants;

use App\Moodle\Entities\MoodleUser;
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
}
