<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\ListChangelog;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $logs = map_collection(Log::with('user')->get());
        $users = User::all();
        return view('tenants.changelog.index', compact('logs','users'));
    }

    /**
     * TODO -> ELIMINAR
     */
    public function add(Request $request)
    {
        Log::create([
            'text' => 'MISSATGE NOu',
            'time' => Carbon::now(),
            'action_type' => 'update',
            'module_type' => 'Incidents',
            'user_id' => 4,
            'icon' => 'home',
            'color' => 'teal'
        ]);
    }
}
