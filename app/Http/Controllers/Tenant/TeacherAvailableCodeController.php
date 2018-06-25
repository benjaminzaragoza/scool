<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowNextAvailableTeacherCode;
use App\Http\Requests\ShowTeachersManagment;
use App\Models\Teacher;

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
