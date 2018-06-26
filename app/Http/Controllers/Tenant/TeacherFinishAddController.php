<?php

namespace App\Http\Controllers\Tenant;
use App\Http\Requests\StoreTeacherFinishAdd;
use App\Mail\TeacherWelcome;
use App\Models\User;
use Mail;

/**
 * Class TeacherFinishAddControllerTest.
 * 
 * @package App\Http\Controllers\Tenant
 */
class TeacherFinishAddController extends Controller
{
    /**
     * Store.
     *
     * @param StoreTeacherFinishAdd $request
     * @return mixed
     */
    public function store(StoreTeacherFinishAdd $request)
    {
        if ($request->welcome_email) {
            Mail::to(User::findOrFail($request->user_id))
                ->queue(new TeacherWelcome());
        }
    }

}
