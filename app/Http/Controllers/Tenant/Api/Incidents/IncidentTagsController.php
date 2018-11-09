<?php

namespace App\Http\Controllers\Tenant\Api\Incidents;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Incidents\ListIncidentTag;
use App\Http\Requests\Incidents\ShowIncidentTag;
use App\Http\Requests\Incidents\StoreIncidentTag;
use App\Models\IncidentTag;

/**
 * Class IncidentTagController.
 *
 * @package App\Http\Controllers\Tenant\Api\Incidents
 */
class IncidentTagsController extends Controller
{

    /**
     * Index
     *
     * @param ListIncidentTag $request
     * @return IncidentTag[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function index(ListIncidentTag $request)
    {
        return IncidentTag::all()->map(function($tag) { return $tag->map();});
    }
    /**
     * Show
     *
     * @param ShowIncidentTag $request
     * @param $tenant
     * @param IncidentTag $tag
     * @return array
     */
    public function show(ShowIncidentTag $request, $tenant, IncidentTag $tag)
    {
        return $tag->map();
    }


    /**
     * Store.
     *
     * @param StoreIncidentTag $request
     * @return mixed
     */
    public function store(StoreIncidentTag $request)
    {
        $tag = IncidentTag::create($request->only(['value','description','color']));
        return $tag->map();
    }
}
