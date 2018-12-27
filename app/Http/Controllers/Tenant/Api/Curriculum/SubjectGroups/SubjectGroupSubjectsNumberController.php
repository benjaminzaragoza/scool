<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups;

use App\Events\SubjectGroups\SubjectGroupSubjectsNumberUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupSubjectsNumberUpdate;
use App\Models\SubjectGroup;

/**
 * Class SubjectGroupSubjectsNumberController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class SubjectGroupSubjectsNumberController extends Controller
{
    /**
     *
     * @param SubjectGroupSubjectsNumberUpdate $request
     * @param $tenant
     * @param SubjectGroup $subjectGroup
     * @return SubjectGroup
     */
    public function update(SubjectGroupSubjectsNumberUpdate $request, $tenant, SubjectGroup $subjectGroup)
    {

        $this->validateSubjectGroupsNumber($subjectGroup, $request->subjects_number);
        $oldSubjectGroup = $subjectGroup->map(false);
        $subjectGroup->subjects_number = $request->subjects_number;
        $subjectGroup->save();
        event(new SubjectGroupSubjectsNumberUpdated($subjectGroup, $oldSubjectGroup));
        return $subjectGroup->map();
    }

    /**
     * @param $subjectGroup
     * @param $number
     */
    protected function validateSubjectGroupsNumber($subjectGroup, $number)
    {
        if($number < count($subjectGroup->subjects)) abort(422,'El nombre total de UFs és superior al nombre de UFs ja assignades al MP');
    }
}
