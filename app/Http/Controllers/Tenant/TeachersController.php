<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ListTeachers;
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
 * Class TeachersController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class TeachersController extends Controller
{
    /**
     * Index.
     *
     * @param ListTeachers $request
     * @return \Illuminate\Support\Collection
     */
    public function index(ListTeachers $request)
    {
        return $this->teachers();
    }

    /**
     * Teachers.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function teachers()
    {
        return collect(TeacherResource::collection(
            Teacher::with([
                'specialty',
                'specialty.jobs',
                'specialty.force',
                'specialty.family',
                'administrativeStatus',
                'user',
                'user.jobs',
                'user.jobs.specialty',
                'user.jobs.family',
                'user.jobs.users',
                'user.jobs.holders',
                'user.person',
                'user.person.birthplace',
                'user.person.identifier',
                'user.person.address',
                'user.person.address.province',
                'user.person.address.location',
                'user.person.media', // TODO
                'department'
            ])->orderByRaw('code + 0')->get()));
    }

    /**
     * Show teachers.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowTeachersManagment $request)
    {
        $pendingTeachers = PendingTeacher::with('specialty')->get();

        $teachers =  $this->teachers();

        $jobs =  collect(JobResource::collection(
            Job::with(
                'type',
                'family',
                'specialty',
                'users',
                'holders',
                'holders.teacher',
                'substitutes',
                'substitutes.teacher')->where('type_id',JobType::findByName('Professor/a')->id)->get()));

        $specialties = Specialty::all();
        $forces = Force::all();
        $administrativeStatuses = AdministrativeStatus::all();
        $departments = Department::all();
        return view('tenants.teachers.show', compact(
            'pendingTeachers','teachers','specialties','forces','administrativeStatuses','jobs','departments'));
    }
}
