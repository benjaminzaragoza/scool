<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teachers\ShowTeachersManagment;
use App\Http\Resources\Tenant\UserCollection;
use App\Models\AdministrativeStatus;
use App\Models\Department;
use App\Models\Force;
use App\Models\Job;
use App\Models\JobType;
use App\Models\PendingTeacher;
use App\Models\Specialty;
use App\Models\Teacher;
use App\Http\Resources\Tenant\Job as JobResource;
use App\Models\User;

/**
 * Class TeachersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class TeachersController extends Controller
{
    /**
     * Show teachers.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ShowTeachersManagment $request)
    {
        $pendingTeachers = PendingTeacher::with('specialty')->get();

        $teachers =  Teacher::teachers();

        $jobs =  collect(JobResource::collection(
            Job::with(
                'type',
                'family',
                'specialty',
                'specialty.department',
                'users',
                'holders',
                'holders.teacher',
                'substitutes',
                'substitutes.teacher')->where('type_id',JobType::findByName('Professor/a')->id)->get()));

        $specialties = Specialty::all();
        $forces = Force::all();
        $administrativeStatuses = AdministrativeStatus::all();
        $departments = Department::all();

        $users = (new UserCollection(User::with(['roles','person','googleUser'])->get()))->transform();

        return view('tenants.teachers.index', compact(
            'pendingTeachers',
            'teachers',
            'specialties',
            'forces',
            'administrativeStatuses',
            'jobs',
            'departments',
            'users'));
    }
}
