<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\StudyFamilyUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Studies\StudyFamilyUpdate;
use App\Models\Family;
use App\Models\Study;

/**
 * Class StudyFamilyController.
 *
 * @package App\Http\Controllers\Api
 */
class StudyFamilyController extends Controller
{
    /**
     * Assign family to study.
     *
     * @param StudyFamilyUpdate $request
     * @param $tenant
     * @param Study $study
     * @param Family $family
     * @return array
     */
    public function update(StudyFamilyUpdate $request, $tenant, Study $study, Family $family)
    {
        $study->assignFamily($family);
        event(new StudyFamilyUpdated($study,$family));
        return $study->map();
    }
}
