<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Changelog\ListUserChangelog;
use App\Models\Log;
use App\Models\User;
use Auth;

/**
 * Class ChangelogUserController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class ChangelogUserController extends Controller
{
    /**
     * Index.
     *
     * @param ListUserChangelog $request
     * @param $tenant
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListUserChangelog $request, $tenant, User $user)
    {
        $logs = map_collection(Log::with(
            'user',
            'loggable.user',
            'loggable.closer',
            'loggable.comments',
            'loggable.tags',
            'loggable.assignees'
        )->fromUser($user)->get());
        $users = User::all();
        return view('tenants.changelog.users.index', compact('logs','users','user'));
    }
}
