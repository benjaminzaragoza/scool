<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Events\Incidents\IncidentDescriptionUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Incidents\UpdateDescriptionIncident;
use App\Models\Incident;

/**
 * Class IncidentsNameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class IncidentsDescriptionController extends Controller
{
    /**
     * Update incident description.
     *
     * @param UpdateDescriptionIncident $request
     * @param $tenant
     * @param Incident $incident
     * @return array
     */
    public function update(UpdateDescriptionIncident $request, $tenant,Incident $incident)
    {
        $oldIncident = clone($incident);
        $incident->description = $request->description;
        $incident->save();
        event(new IncidentDescriptionUpdated($incident,$oldIncident));
        return $incident->map();
    }
}
