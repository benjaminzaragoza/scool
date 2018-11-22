<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Changelog\ListUserChangelog;
use App\Models\Log;
use App\Models\Module;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListUserChangelog $request, $tenant, Module $module)
    {
        $logs = map_collection(Log::with('user')->fromUser(Auth::user())->get());
        $users = User::all();
        return view('tenants.changelog.users.index', compact('logs','users','module'));
    }
}
