<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups;

use App\Events\SubjectGroups\SubjectGroupStored;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupsDestroy;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupsIndex;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupsStore;
use App\Models\SubjectGroup;

/**
 * Class SubjectsController.
 *
 * @package App\Http\Controllers\Api
 */
class SubjectGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SubjectGroupsIndex $request
     * @return mixed
     */
    public function index(SubjectGroupsIndex $request)
    {
        return map_collection(SubjectGroup::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubjectGroupsStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectGroupsStore $request)
    {
        $subjectGroup = SubjectGroup::create([
            'number' => $request->number,
            'code' => $request->code,
            'name' => $request->name,
            'shortname' => $request->shortname,
            'description' => $request->description,
            'study_id' => $request->study_id,
            'hours' => $request->hours,
            'free_hours' => $request->free_hours,
            'week_hours' => $request->week_hours,
            'type' => $request->type,
            'start' => $request->start,
            'end' => $request->end
        ]);
        event(new SubjectGroupStored($subjectGroup));
        return $subjectGroup->map();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param SubjectGroupsDestroy $request
     * @param $tenant
     * @param SubjectGroup $subjectGroup
     * @return SubjectGroup
     * @throws \Exception
     */
    public function destroy(SubjectGroupsDestroy $request, $tenant, SubjectGroup $subjectGroup)
    {
        $oldSubjectGroup = $subjectGroup->map();
        $subjectGroup->delete();
        event(new SubjectGroupStored($oldSubjectGroup));
        return $subjectGroup;
    }
}
