<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Events\Incidents\IncidentClosed;
use App\Events\Incidents\IncidentOpened;
use App\Http\Requests\Incidents\CloseIncident;
use App\Http\Requests\Incidents\OpenIncident;
use App\Models\Incident;
use App\Http\Controllers\Controller;

/**
 * Class ClosedIncidentsController.
 *
 * @package App\Http\Controllers\Api
 */
class IncidentsClosedController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CloseIncident $request
     * @param $tenant
     * @param Incident $incident
     * @return array
     */
    public function store(CloseIncident $request, $tenant, Incident $incident)
    {
        $incident = $incident->close();
        event(new IncidentClosed($incident));
        return $incident->map();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param OpenIncident $request
     * @param $tenant
     * @param Incident $incident
     * @return array
     */
    public function destroy(OpenIncident $request, $tenant, Incident $incident)
    {
        $incident = $incident->open();
        event(new IncidentOpened($incident));
        return $incident->map();
    }
}
