<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ListTeachers;
use App\Http\Requests\ShowNextAvailableTeacherCode;
use App\Http\Requests\ShowTeachersManagment;
use App\Models\AdministrativeStatus;
use App\Models\Department;
use App\Models\Force;
use App\Models\Job;
use App\Models\JobType;
use App\Models\PendingTeacher;
use App\Models\Specialty;
use App\Models\Teacher;
use App\Http\Resources\Tenant\Teacher as TeacherResource;
use App\Http\Resources\Tenant\Job as JobResource;

/**
 * Class TeacherAvailableCodeController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class TeacherAvailableCodeController extends Controller
{
    /**
     * Show next available code.
     *
     * @param ShowTeachersManagment $request
     * @return string
     */
    public function show(ShowNextAvailableTeacherCode $request)
    {
        return Teacher::firstAvailableCode();
    }
}
