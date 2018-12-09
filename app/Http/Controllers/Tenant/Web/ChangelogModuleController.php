<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Changelog\ListModuleChangelog;
use App\Models\Log;
use App\Models\Module;
use App\Models\User;

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
        $logs = map_collection(Log::with(
            'user',
            'loggable.user',
            'loggable.closer',
            'loggable.comments',
            'loggable.tags',
            'loggable.assignees'
        )->module($module->name)->get());
        $users = User::all();
        return view('tenants.changelog.modules.index', compact('logs','users','module'));
    }
}
