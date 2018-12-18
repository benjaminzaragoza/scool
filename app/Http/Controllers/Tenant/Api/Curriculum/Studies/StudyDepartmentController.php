<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\StudyDepartmentUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Studies\StudyDepartmentUpdate;
use App\Models\Department;
use App\Models\Study;

/**
 * Class StudyDepartmentController.
 *
 * @package App\Http\Controllers\Api
 */
class StudyDepartmentController extends Controller
{
    /**
     * Assign department to study.
     *
     * @param StudyDepartmentUpdate $request
     * @param $tenant
     * @param Study $study
     * @param Department $department
     * @return array
     */
    public function update(StudyDepartmentUpdate $request, $tenant, Study $study, Department $department)
    {
        $study->assignDepartment($department);
        event(new StudyDepartmentUpdated($study,$department));
        return $study->map();
    }


}
