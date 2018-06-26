<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowTeacherWelcomeEmail;
use App\Mail\TeacherWelcome;

/**
 * Class TeacherWelcomeEmailController.
 *
 * @package App\Http\Controllers\Tenant
 */
class TeacherWelcomeEmailController extends Controller
{
    public function show(ShowTeacherWelcomeEmail $request)
    {
        return new TeacherWelcome();
    }
}
