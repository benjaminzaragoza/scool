<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Http\Requests\Incidents\CloseIncident;
use App\Http\Requests\Incidents\OpenIncident;
use App\Mail\Incidents\IncidentClosed;
use App\Mail\Incidents\IncidentOpened;
use App\Models\Incident;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Mail;

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
     * @return Incident
     */
    public function store(CloseIncident $request, $tenant, Incident $incident)
    {
        $incident = $incident->close();
        Mail::to($request->user())->cc(Setting::get('incidents_manager_email'))->queue(new IncidentClosed($incident));
        return $incident->map();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param OpenIncident $request
     * @param $tenant
     * @param Incident $incident
     * @return Incident
     */
    public function destroy(OpenIncident $request, $tenant, Incident $incident)
    {
        $incident = $incident->open();
        Mail::to($request->user())->cc(Setting::get('incidents_manager_email'))->queue(new IncidentOpened($incident));
        return $incident->map();
    }
}
