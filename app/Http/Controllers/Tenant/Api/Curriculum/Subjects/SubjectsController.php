<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Subjects;

use App\Events\Subjects\SubjectStored;
use App\Events\Subjects\SubjectDeleted;
use App\Http\Requests\Curriculum\Subjects\SubjectIndex;
use App\Http\Requests\Curriculum\Subjects\SubjectShow;
use App\Http\Requests\Curriculum\Subjects\SubjectStore;
use App\Http\Requests\Curriculum\Subjects\SubjectDestroy;
use App\Http\Controllers\Controller;
use App\Models\Subject;

/**
 * Class SubjectsController.
 *
 * @package App\Http\Controllers\Api
 */
class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SubjectIndex $request
     * @return mixed
     */
    public function index(SubjectIndex $request)
    {
        return map_collection(Subject::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubjectStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectStore $request)
    {
        $subject = Subject::create([
            'name' => $request->name,
            'shortname' => $request->shortname,
            'code' => $request->code,
            'department_id' => $request->department,
            'family_id' => $request->family
        ]);
        event(new SubjectStored($subject));
        return $subject->map();
    }

    /**
     * Display the specified resource.
     *
     * @param SubjectShow $request
     * @param $tenant
     * @param Subject $subject
     * @return array
     */
    public function show(SubjectShow $request, $tenant,Subject $subject)
    {
//        event(new IncidentShowed($incident));
//        return $incident->map();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SubjectDestroy $request
     * @param $tenant
     * @param Subject $incident
     * @return Subject
     * @throws \Exception
     */
    public function destroy(SubjectDestroy $request, $tenant, Subject $subject)
    {
        $oldIncident = $subject->map(false);
        $subject->delete();
        event(new SubjectDeleted($oldIncident));
        return $subject;
    }
}
