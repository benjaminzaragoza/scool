<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\ShowIncidentTag;
use App\Models\IncidentTag;
use Illuminate\Http\Request;

/**
 * Class IncidentTagController.
 *
 * @package App\Http\Controllers\Tenant\Api\Incidents
 */
class IncidentTagsController extends Controller
{
    public function show(ShowIncidentTag $request, $tenant, IncidentTag $tag)
    {
        return $tag->map();
    }
}
