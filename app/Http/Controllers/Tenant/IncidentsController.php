<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Incidents\ListIncidents;
use App\Http\Requests\Incidents\ShowIncident;
use App\Models\Incident;
use App\Models\IncidentTag;
use App\Models\Setting;
use App\Models\User;

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
        $incidents = Incident::getIncidents();
        $incident_users = Incident::usersWithIncidentsRoles();
        $tags = IncidentTag::all();
        return view('tenants.incidents.index',compact('incidents','incident_users','tags'));
    }

    /**
     * Show.
     *
     * @param ShowIncident $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowIncident $request, $tenant, Incident $incident)
    {
        $incidents = Incident::getIncidents();
        $incident_users = Incident::usersWithIncidentsRoles();
        $tags = IncidentTag::all();
        return view('tenants.incidents.index',compact(['incidents','incident','incident_users','tags']));
    }
}
