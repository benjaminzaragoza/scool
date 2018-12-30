<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups;

use App\Events\SubjectGroups\SubjectGroupShortnameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupShortnameUpdate;
use App\Models\SubjectGroup;

/**
 * Class SubjectGroupsShortnameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class SubjectGroupsShortnameController extends Controller
{
    /**
     * Update study name.
     *
     * @param SubjectGroupShortnameUpdate $request
     * @param $tenant
     * @param SubjectGroup $subjectGroup
     * @return array
     */
    public function update(SubjectGroupShortnameUpdate $request, $tenant, SubjectGroup $subjectGroup)
    {
        $oldSubjectGroup = $subjectGroup->map(false);
        $subjectGroup->shortname = $request->shortname;
        $subjectGroup->save();
        event(new SubjectGroupShortnameUpdated($subjectGroup, $oldSubjectGroup));
        return $subjectGroup->map();
    }
}
