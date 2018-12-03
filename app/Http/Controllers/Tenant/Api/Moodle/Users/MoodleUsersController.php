<?php

namespace App\Http\Controllers\Tenant\Api\Moodle\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moodle\Users\MoodleUserDestroy;
use App\Http\Requests\Moodle\Users\MoodleUserIndex;
use App\Http\Requests\Moodle\Users\MoodleUserStore;
use App\Moodle\Entities\MoodleUser;
use Cache;

/**
 * Class MoodleUsersController.
 *
 * @package App\Http\Controllers\Api
 */
class MoodleUsersController extends Controller
{
    /**
     * Index.
     *
     * @param MoodleUserIndex $request
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(MoodleUserIndex $request)
    {
        return MoodleUser::all();
    }

    /**
     * Store.
     *
     * @param MoodleUserStore $request
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(MoodleUserStore $request)
    {
        $result = MoodleUser::store($request->user);
        Cache::forget('scool_moodle_users');
        return MoodleUser::get($result->username);
    }

    /**
     * Destroy.
     *
     * @param MoodleUserDestroy $request
     * @param $tenant
     * @param $moodleuser
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroy(MoodleUserDestroy $request, $tenant, $moodleuser)
    {
        MoodleUser::destroy($moodleuser);
        Cache::forget('scool_moodle_users');
    }
}
