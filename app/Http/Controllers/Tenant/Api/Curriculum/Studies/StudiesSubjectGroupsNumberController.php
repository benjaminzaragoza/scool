<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\StudySubjectGroupsNumberUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Studies\StudySubjectGroupsNumberUpdate;
use App\Models\Incident;
use App\Models\Study;

/**
 * Class StudiesSubjectGroupsNumberController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class StudiesSubjectGroupsNumberController extends Controller
{
    /**
     * Update study name.
     *
     * @param StudySubjectGroupsNumberUpdate $request
     * @param $tenant
     * @param Incident $incident
     * @return Incident
     */
    public function update(StudySubjectGroupsNumberUpdate $request, $tenant, Study $study)
    {
        $oldStudy = $study->map(false);
        $study->subject_groups_number = $request->subject_groups_number;
        $study->save();
        event(new StudySubjectGroupsNumberUpdated($study, $oldStudy));
        return $study->map();
    }
}
