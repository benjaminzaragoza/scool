<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ListIncidents;
use App\Models\Incident;

/**
 * Class IncidentsController.
 *
 * @package App\Http\Controllers\Tenant
 */
class IncidentsController extends Controller{

    public function index(ListIncidents $Request)
    {
        $incidents = Incident::all();
        return view('tenants.incidents.index',compact('incidents'));
    }
}