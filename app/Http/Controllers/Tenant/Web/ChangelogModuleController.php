<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\ListChangelog;
use App\Http\Requests\Incidents\ListModuleChangelog;
use App\Models\Log;
use App\Models\Module;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class ChangelogModuleController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class ChangelogModuleController extends Controller
{
    /**
     * Index.
     *
     * @param ListModuleChangelog $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListModuleChangelog $request, $tenant, Module $module)
    {
        $logs = map_collection(Log::with('user')->module($module->name)->get());
        $users = User::all();
        return view('tenants.changelog.index', compact('logs','users'));
    }
}
