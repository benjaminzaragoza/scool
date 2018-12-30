<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups;

use App\Events\SubjectGroups\SubjectGroupNameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupNameUpdate;
use App\Models\Incident;
use App\Models\SubjectGroup;

/**
 * Class SubjectGroupsNameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class SubjectGroupsNameController extends Controller
{
    /**
     * Update study name.
     *
     * @param SubjectGroupNameUpdate $request
     * @param $tenant
     * @param SubjectGroup $subjectGroup
     * @return Incident
     */
    public function update(SubjectGroupNameUpdate $request, $tenant, SubjectGroup $subjectGroup)
    {
        $oldSubjectGroup = $subjectGroup->map(false);
        $subjectGroup->name = $request->name;
        $subjectGroup->save();
        event(new SubjectGroupNameUpdated($subjectGroup, $oldSubjectGroup));
        return $subjectGroup->map();
    }
}
