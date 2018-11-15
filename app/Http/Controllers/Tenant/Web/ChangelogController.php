<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\ListChangelog;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;

/**
 * Class ChangelogController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class ChangelogController extends Controller
{
    /**
     * Index.
     *
     * @param ListChangelog $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListChangelog $request)
    {
        // TODO
//        $log1 = Log::create([
//            'text' => 'Ha creat la incidència TODO_LINK_INCIDENCIA',
//            'time' => Carbon::now(),
//            'action_type' => 'update',
//            'module_type' => 'Incidents',
//            'user_id' => 4,
//            'icon' => 'home',
//            'color' => 'teal'
//        ]);
//        $log2 = Log::create([
//            'text' => 'Ha modificat la incidència TODO_LINK_INCIDENCIA',
//            'time' => Carbon::now(),
//            'action_type' => 'update',
//            'module_type' => 'Incidents',
//            'user_id' => 1,
//            'icon' => 'home',
//            'color' => 'teal'
//        ]);
//        $log3 = Log::create([
//            'text' => 'Ha modificat la incidència TODO_LINK_INCIDENCIA',
//            'time' => Carbon::now(),
//            'action_type' => 'update',
//            'module_type' => 'Incidents',
//            'user_id' => 2,
//            'icon' => 'home',
//            'color' => 'teal'
//        ]);

        $logs = map_collection(Log::with('user')->get());
        $users = User::all();
        return view('tenants.changelog.index', compact('logs','users'));
    }
}
