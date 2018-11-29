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
}
