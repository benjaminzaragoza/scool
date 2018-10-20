<?php

namespace App\Http\Controllers\Tenant\Api;

use App\Http\Requests\CloseIncident;
use App\Http\Requests\ListIncidents;
use App\Http\Requests\OpenIncident;
use App\Http\Requests\ShowIncident;
use App\Http\Requests\StoreIncident;
use App\Models\Incident;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class ClosedIncidentsController.
 *
 * @package App\Http\Controllers\Api
 */
class ClosedIncidentsController extends Controller
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
        return $incident->close()->map();
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
        return $incident->open()->map();
    }
}
