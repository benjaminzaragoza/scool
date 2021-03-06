<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\StudyNameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Studies\StudyNameUpdate;
use App\Models\Study;

/**
 * Class StudiesNameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class StudiesNameController extends Controller
{
    /**
     * Update study name.
     *
     * @param StudyNameUpdate $request
     * @param $tenant
     * @param Study $study
     * @return array
     */
    public function update(StudyNameUpdate $request, $tenant, Study $study)
    {
        $oldStudy = $study->map(false);
        $study->name = $request->name;
        $study->save();
        event(new StudyNameUpdated($study, $oldStudy));
        return $study->map();
    }
}
