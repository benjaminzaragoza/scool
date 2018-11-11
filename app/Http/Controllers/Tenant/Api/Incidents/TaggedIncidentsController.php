<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Http\Requests\Incidents\DestroyIncidentTag;
use App\Http\Requests\Incidents\StoreIncidentTag;
use App\Mail\Incidents\IncidentTagged;
use App\Mail\Incidents\IncidentUntagged;
use App\Models\Setting;
use App\Models\IncidentTag;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\DestroyIncidentAssignee;
use App\Http\Requests\Incidents\StoreIncidentAssignee;
use App\Models\Incident;
use Mail;

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
    public function store(StoreIncidentTag $request, $tenant, Incident $incident, IncidentTag $tag)
    {
        $incident->addTag($tag);
        Mail::to($request->user())->cc(Setting::get('incidents_manager_email'))->queue(new IncidentTagged($incident));
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
    public function destroy(DestroyIncidentTag $request, $tenant, Incident $incident, IncidentTag $tag)
    {
        $incident->tags()->detach($tag->id);
        Mail::to($request->user())->cc(Setting::get('incidents_manager_email'))->queue(new IncidentUntagged($incident));
    }
}
