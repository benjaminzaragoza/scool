<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Models\User;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\DestroyIncidentAssignee;
use App\Http\Requests\Incidents\StoreIncidentAssignee;
use App\Models\Incident;

/**
 * Class IncidentAssigneesController.
 *
 * @package App\Http\Controllers\Tenant\Api\Incidents
 */
class IncidentAssigneesController extends Controller
{
    /**
     * Store.
     *
     * @param StoreIncidentAssignee $request
     * @return mixed
     */
    public function store(StoreIncidentAssignee $request, $tenant, Incident $incident, User $user)
    {
        $incident->addAssignee($user);
    }

    /**
     * Destroy.
     *
     * @param DestroyIncidentAssignee $request
     * @param $tenant
     * @param Incident $incident
     * @param User $user
     * @return void
     */
    public function destroy(DestroyIncidentAssignee $request, $tenant, Incident $incident, User $user)
    {
        $incident->assignees()->detach($user->id);
    }
}
