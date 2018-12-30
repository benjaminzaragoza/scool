<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups;

use App\Events\SubjectGroups\SubjectGroupCodeUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupCodeUpdate;
use App\Models\Incident;
use App\Models\SubjectGroup;

/**
 * Class SubjectGroupsCodeController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class SubjectGroupsCodeController extends Controller
{
    /**
     * Update subjectGroup code.
     *
     * @param SubjectGroupCodeUpdate $request
     * @param $tenant
     * @param SubjectGroup $subjectGroup
     * @return array
     */
    public function update(SubjectGroupCodeUpdate $request, $tenant, SubjectGroup $subjectGroup)
    {
        $oldSubjectGroup = $subjectGroup->map(false);
        $subjectGroup->code = $request->code;
        $subjectGroup->save();
        event(new SubjectGroupCodeUpdated($subjectGroup, $oldSubjectGroup));
        return $subjectGroup->map();
    }
}
