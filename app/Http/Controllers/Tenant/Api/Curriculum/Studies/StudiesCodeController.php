<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\SubjectGroupCodeUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Studies\StudyCodeUpdate;
use App\Models\Incident;
use App\Models\Study;

/**
 * Class StudiesCodeController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class StudiesCodeController extends Controller
{
    /**
     * Update study code.
     *
     * @param StudyCodeUpdate $request
     * @param $tenant
     * @param Incident $incident
     * @return Incident
     */
    public function update(StudyCodeUpdate $request, $tenant, Study $study)
    {
        $oldStudy = $study->map(false);
        $study->code = $request->code;
        $study->save();
        event(new SubjectGroupCodeUpdated($study, $oldStudy));
        return $study->map();
    }
}
