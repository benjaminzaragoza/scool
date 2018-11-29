<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Changelog\ListChangelog;
use App\Http\Requests\Moodle\MoodleIndex;
use App\Moodle\Entities\MoodleUser;
use Cache;

/**
 * Class MoodleController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class MoodleController extends Controller
{
    /**
     * Index.
     *
     * @param ListChangelog $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(MoodleIndex $request)
    {
        $users = Cache::rememberForever('scool_moodle_users', function () {
            return collect(MoodleUser::all());
        });

        return view('tenants.moodle.index', compact('users'));
    }
}
