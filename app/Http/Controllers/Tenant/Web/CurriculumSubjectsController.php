<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\CurriculumIndex;
use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\StudyTag;
use App\Models\Subject;

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
//        $departments = map_collection(Department::all());
//        $families = map_collection(Family::all());
//        $tags = map_collection(StudyTag::all());
        return view('tenants.curriculum.subjects.index', compact('subjects'));
    }
}
