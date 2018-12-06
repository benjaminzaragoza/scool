<?php

namespace App\Http\Controllers\Tenant\Api\Moodle\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moodle\Users\MoodleUserPasswordUpdate;
use App\Models\MoodleUser;

/**
 * Class MoodleUsersPasswordController.
 *
 * @package App\Http\Controllers\Api
 */
class MoodleUsersPasswordController extends Controller
{
    /**
     * Update password.
     *
     * @param MoodleUserPasswordUpdate $request
     * @param $tenant
     * @param $moodleuser
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(MoodleUserPasswordUpdate $request, $tenant, $moodleuser)
    {
        MoodleUser::change_password($moodleuser,$request->password);
    }
}
