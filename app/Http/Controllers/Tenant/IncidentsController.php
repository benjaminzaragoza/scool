<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Incidents\ListIncidents;
use App\Models\Incident;
use Gate;

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
    public function index(ListIncidents $request)
    {
        create_setting('incidents_manager_email','incidencies@iesebre.com','IncidentsManager');
        dump(config('incidents.incidents_manager_email'));
        $incidents = Incident::getIncidents();
        return view('tenants.incidents.index',compact('incidents'));
    }
}
