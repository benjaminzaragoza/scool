<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\CurriculumIndex;
use App\Models\Course;
use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\Subject;
use App\Models\SubjectGroup;

/**
 * Class CurriculumSubjectsController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class CurriculumSubjectsController extends Controller
{
    /**
     * Index.
     *
     * @param CurriculumIndex $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CurriculumIndex $request)
    {

        $subjects = map_collection(Subject::all());
        $studies = map_collection(Study::all());
        $subject_groups = map_collection(SubjectGroup::all());
        $courses = map_collection(Course::all());
        $departments = map_collection(Department::all());
        $families = map_collection(Family::all());
        return view('tenants.curriculum.subjects.index',
            compact('subjects','studies','subject_groups','courses','departments','families'));
    }
}
