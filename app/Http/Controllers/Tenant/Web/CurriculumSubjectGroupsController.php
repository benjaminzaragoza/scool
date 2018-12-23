<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupsIndex;
use App\Models\Course;
use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\Subject;
use App\Models\SubjectGroup;

/**
 * Class CurriculumSubjectGroupsController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class CurriculumSubjectGroupsController extends Controller
{
    /**
     * Index.
     *
     * @param SubjectGroupsIndex $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(SubjectGroupsIndex $request)
    {
        $subjectGroups = map_collection(SubjectGroup::with('study')->get());
        return view('tenants.curriculum.subjectGroups.index',
            compact('subjectGroups'));
    }
}
