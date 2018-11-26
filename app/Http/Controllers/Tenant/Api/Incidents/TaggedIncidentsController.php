<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Events\Incidents\IncidentTagAdded;
use App\Events\Incidents\IncidentTagRemoved;
use App\Http\Requests\Incidents\DestroyTaggedIncident;
use App\Http\Requests\Incidents\StoreTaggedIncident;
use App\Models\IncidentTag;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\DestroyIncidentAssignee;
use App\Http\Requests\Incidents\StoreIncidentAssignee;
use App\Models\Incident;

/**
 * Class TaggedIncidentsController.
 *
 * @package App\Http\Controllers\Tenant\Api\Incidents
 */
class TaggedIncidentsController extends Controller
{
    /**
     * Store.
     *
     * @param StoreIncidentAssignee $request
     * @return mixed
     */
    public function store(StoreTaggedIncident $request, $tenant, Incident $incident, IncidentTag $tag)
    {
        $incident->addTag($tag);
        event(new IncidentTagAdded($incident,$tag));
    }

    /**
     * Destroy.
     *
     * @param DestroyIncidentAssignee $request
     * @param $tenant
     * @param Incident $incident
     * @param IncidentTag $tag
     * @return void
     */
    public function destroy(DestroyTaggedIncident $request, $tenant, Incident $incident, IncidentTag $tag)
    {
        $incident->tags()->detach($tag->id);
        event(new IncidentTagRemoved($incident,$tag));

    }
}
