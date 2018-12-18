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
     * @return mixed
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
        $study = Study::create([
            'name' => $request->name,
            'shortname' => $request->shortname,
            'code' => $request->code,
            'department_id' => $request->department,
            'family_id' => $request->family
        ]);
//        event(new IncidentStored($study));
        return $study->map();
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
    public function destroy(StudyDestroy $request, $tenant, Study $study)
    {
//        $oldIncident = $study->map(false);
        $study->delete();
//        event(new IncidentDeleted($oldIncident));
        return $study;
    }
}
