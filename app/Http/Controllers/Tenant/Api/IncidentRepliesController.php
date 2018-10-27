<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Requests\Incidents\ListIncidentReplies;
use App\Models\Incident;

/**
 * Class IncidentRepliesController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class IncidentRepliesController
{
    /**
     * @param ListIncidentReplies $request
     * @param $tenant
     * @param Incident $incident
     * @return mixed
     */
    public function index(ListIncidentReplies $request, $tenant, Incident $incident)
    {
        return $incident->replies;
    }
}
