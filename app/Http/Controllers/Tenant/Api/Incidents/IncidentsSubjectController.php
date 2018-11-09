<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

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
        $incident->subject = $request->subject;
        $incident->save();
        return $incident->map();
    }
}
