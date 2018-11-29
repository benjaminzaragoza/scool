<?php

namespace App\Http\Controllers\Tenant\Api\Moodle\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moodle\Users\MoodleUserCheckStore;
use App\Moodle\Entities\MoodleUser;

/**
 * Class MoodleUsersCheckController.
 *
 * @package App\Http\Controllers\Api
 */
class MoodleUsersCheckController extends Controller
{
    public function store(MoodleUserCheckStore $request)
    {
        $foundUsers = MoodleUser::getByIdNumber($request->user['id']);
        if ($foundUsers) {
            return [
                'status' => 'Error',
                'message' => "S'han trobat usuari/s amb idnumber coincident",
                'users' => $foundUsers
            ];
        }
    }
}
