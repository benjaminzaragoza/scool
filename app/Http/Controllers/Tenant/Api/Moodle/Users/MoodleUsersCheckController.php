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
    /**
     * Store.
     *
     * @param MoodleUserCheckStore $request
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(MoodleUserCheckStore $request)
    {
        $foundUsers = MoodleUser::getByIdNumber($request->user['id']);
        $error = false;
        $message = [];
        $users = [];
        if ($foundUsers) {
            $error=true;
            $message[]="S'han trobat usuari/s amb idnumber coincident";
            $users = array_merge($users,$foundUsers);
        }
        $foundUsersEmail = MoodleUser::get($request->user['email']);
        if ($foundUsersEmail) {
            $error=true;
            $message[]="S'han trobat usuari/s amb email coincident";
            $users[] = $foundUsersEmail;
        }
        return [
            'status' => $error ? 'Error' : 'Success',
            'message' => $message,
            'users' => $users
        ];
    }
}
