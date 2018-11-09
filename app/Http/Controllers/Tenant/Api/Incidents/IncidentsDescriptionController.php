<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

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
     * @return Incident
     */
    public function update(UpdateDescriptionIncident $request, $tenant,Incident $incident)
    {
        $incident->description = $request->description;
        $incident->save();
        return $incident->map();
    }
}
