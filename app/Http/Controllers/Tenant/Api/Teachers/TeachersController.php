<?php

namespace App\Http\Controllers\Tenant\Api\Teachers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestroyTeacher;
use App\Http\Requests\ListTeachers;
use App\Http\Requests\StoreTeacher;
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
use App\Http\Resources\Tenant\Teacher as TeacherResource;
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
     * Index.
     *
     * @param ListTeachers $request
     * @return \Illuminate\Support\Collection
     */
    public function index(ListTeachers $request)
    {
        return Teacher::teachers();
    }

    /**
     * Store.
     *
     * @param StoreTeacher $request
     * @return mixed
     */
    public function store(StoreTeacher $request)
    {
        return Teacher::create([
            'user_id' => $request->user_id,
            'code' => $request->code,
            'department_id' => $request->department_id,
            'administrative_status_id' => $request->administrative_status_id,
            'specialty_id' => $request->specialty_id,
        ]);
    }

    /**
     * Destroy
     *
     * @param DestroyTeacher $request
     * @param $tenant
     * @param Teacher $teacher
     * @return Teacher
     * @throws \Exception
     */
    public function destroy(DestroyTeacher $request, $tenant, Teacher $teacher)
    {
        $teacher->delete();
        return $teacher;
    }
}
