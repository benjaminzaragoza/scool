<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ListIncidents;
use App\Models\Incident;
use Gate;
use Spatie\Permission\Models\Permission;

/**
 * Class IncidentsController.
 *
 * @package App\Http\Controllers\Tenant
 */
class IncidentsController extends Controller{

    /**
     * Index.
     *
     * @param ListIncidents $Request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListIncidents $Request)
    {
        $incidents = Incident::getIncidents();
        return view('tenants.incidents.index',compact('incidents'));
    }
}
