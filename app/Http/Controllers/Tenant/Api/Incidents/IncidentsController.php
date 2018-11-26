<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Events\Incidents\IncidentDeleted;
use App\Events\Incidents\IncidentShowed;
use App\Events\Incidents\IncidentStored;
use App\Http\Requests\Incidents\DeleteIncident;
use App\Http\Requests\Incidents\ListIncidents;
use App\Http\Requests\Incidents\ShowIncident;
use App\Http\Requests\Incidents\StoreIncident;
use App\Models\Incident;
use App\Http\Controllers\Controller;

/**
 * Class IncidentsController.
 *
 * @package App\Http\Controllers\Api
 */
class IncidentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ListIncidents $request
     * @return Incident[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(ListIncidents $request)
    {
        return Incident::getIncidents();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreIncident $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIncident $request)
    {
        $incident = Incident::create($request->only('subject','description'))->assignUser($request->user());
        event(new IncidentStored($incident));
        return $incident->load(['user'])->map();
    }

    /**
     * Display the specified resource.
     *
     * @param ShowIncident $request
     * @param $tenant
     * @param Incident $incident
     * @return array
     */
    public function show(ShowIncident $request, $tenant,Incident $incident)
    {
        event(new IncidentShowed($incident));
        return $incident->map();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteIncident $request
     * @param $tenant
     * @param Incident $incident
     * @return Incident
     * @throws \Exception
     */
    public function destroy(DeleteIncident $request, $tenant, Incident $incident)
    {
        $oldIncident = clone($incident);
        $incident->delete();
        event(new IncidentDeleted($oldIncident));
        return $incident;
    }
}
