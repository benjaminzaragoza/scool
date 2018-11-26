<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Events\Incidents\IncidentSubjectUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Incidents\UpdateSubjectIncident;
use App\Models\Incident;

/**
 * Class IncidentsSubjectController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class IncidentsSubjectController extends Controller
{
    /**
     * Update incident subject.
     *
     * @param UpdateSubjectIncident $request
     * @param $tenant
     * @param Incident $incident
     * @return Incident
     */
    public function update(UpdateSubjectIncident $request, $tenant,Incident $incident)
    {
        $oldIncident = clone($incident);
        $incident->subject = $request->subject;
        $incident->save();
        event(new IncidentSubjectUpdated($incident, $oldIncident));
        return $incident->map();
    }
}
