<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Changelog\ListLoggableChangelog;
use App\Http\Requests\Changelog\ListUserChangelog;
use App\Models\Log;
use App\Models\User;
use Auth;

/**
 * Class ChangelogLoggableController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class ChangelogLoggableController extends Controller
{
    /**
     * Index
     * @param ListLoggableChangelog $request
     * @param $tenant
     * @param $loggable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListLoggableChangelog $request, $tenant, $loggable)
    {
        $logs = map_collection(Log::with('user')->fromLoggable($loggable)->get());
        $users = User::all();
        return view('tenants.changelog.loggable.index', compact('logs','users','loggable'));
    }
}
