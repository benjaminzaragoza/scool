<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

//use App\Events\Incidents\IncidentDeleted;
//use App\Events\Incidents\IncidentShowed;
//use App\Events\Incidents\IncidentStored;
use App\Http\Requests\Curriculum\Studies\StudyIndex;
use App\Http\Requests\Curriculum\Studies\StudyShow;
use App\Http\Requests\Curriculum\Studies\StudyStore;
use App\Http\Requests\Curriculum\Studies\StudyUpdate;
use App\Http\Requests\Curriculum\Studies\StudyDestroy;
use App\Http\Controllers\Controller;
use App\Models\Study;

/**
 * Class StudiesController.
 *
 * @package App\Http\Controllers\Api
 */
class StudiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param StudyIndex $request
     * @return Incident[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(StudyIndex $request)
    {
        return map_collection(Study::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StudyStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudyStore $request)
    {
//        $incident = Incident::create($request->only('subject','description'))->assignUser($request->user());
//        event(new IncidentStored($incident));
//        return $incident->load(['user'])->map();
    }

    /**
     * Display the specified resource.
     *
     * @param StudyShow $request
     * @param $tenant
     * @param Study $study
     * @return array
     */
    public function show(StudyShow $request, $tenant,Study $study)
    {
//        event(new IncidentShowed($incident));
//        return $incident->map();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StudyDestroy $request
     * @param $tenant
     * @param Study $incident
     * @return Study
     * @throws \Exception
     */
    public function destroy(StudyDestroy $request, $tenant, Study $incident)
    {
//        $oldIncident = $incident->map(false);
//        $incident->delete();
//        event(new IncidentDeleted($oldIncident));
//        return $incident;
    }
}
