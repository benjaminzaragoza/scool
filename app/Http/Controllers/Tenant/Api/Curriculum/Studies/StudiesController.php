<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\StudyStored;
use App\Http\Requests\Curriculum\Studies\PositionIndex;
use App\Http\Requests\Curriculum\Studies\PositionShow;
use App\Http\Requests\Curriculum\Studies\PositionStore;
use App\Http\Requests\Curriculum\Studies\PositionUpdate;
use App\Http\Requests\Curriculum\Studies\PositionDestroy;
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
     * @param PositionIndex $request
     * @return mixed
     */
    public function index(PositionIndex $request)
    {
        return map_collection(Study::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PositionStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionStore $request)
    {
        $study = Study::create([
            'name' => $request->name,
            'shortname' => $request->shortname,
            'code' => $request->code,
            'department_id' => $request->department,
            'family_id' => $request->family,
            'subjects_number' => $request->subjects_number,
            'subject_groups_number' => $request->subject_groups_number
        ]);
        event(new StudyStored($study));
        return $study->map();
    }

    /**
     * Display the specified resource.
     *
     * @param PositionShow $request
     * @param $tenant
     * @param Study $study
     * @return array
     */
    public function show(PositionShow $request, $tenant, Study $study)
    {
//        event(new IncidentShowed($incident));
//        return $incident->map();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PositionDestroy $request
     * @param $tenant
     * @param Study $incident
     * @return Study
     * @throws \Exception
     */
    public function destroy(PositionDestroy $request, $tenant, Study $study)
    {
//        $oldIncident = $study->map(false);
        $study->delete();
//        event(new IncidentDeleted($oldIncident));
        return $study;
    }
}
