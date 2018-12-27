<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\Subjects\SubjectIndex;
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
     * @param SubjectIndex $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(SubjectIndex $request)
    {
        $subjects = map_collection(Subject::with('study','course','subject_group')->get());
        $studies = map_collection(Study::with('family','department','tags','subjectGroups','subjectGroups.study','subjectGroups.tags')->get());
        $subject_groups = map_collection(SubjectGroup::with('study','tags')->get());
        $courses = map_collection(Course::with('study')->get());
        $departments = map_collection(Department::all());
        $families = map_collection(Family::all());
        return view('tenants.curriculum.subjects.index',
            compact('subjects','studies','subject_groups','courses','departments','families'));
    }
}
