<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Changelog\ListChangelog;
use App\Http\Requests\Moodle\MoodleIndex;
use App\Models\User;
use App\Models\MoodleUser;
use Cache;

/**
 * Class MoodleUsersController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class MoodleUsersController extends Controller
{
    /**
     * Index.
     *
     * @param ListChangelog $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(MoodleIndex $request)
    {
        $action = $request->action;
        $users = Cache::rememberForever('scool_moodle_users', function () {
            return collect(MoodleUser::all());
        });
        $localUsers = map_collection(User::with(['roles','permissions','googleUser','person'])->get());
        $users = $users->map(function($user) use ($localUsers) {
           return MoodleUser::adapt($user, $localUsers);
        });

        return view('tenants.moodle.index', compact('users','localUsers','action'));
    }
}
