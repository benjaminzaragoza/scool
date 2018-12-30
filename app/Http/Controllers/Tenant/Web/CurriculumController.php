<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\CurriculumIndex;
use App\Models\Course;
use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\StudyTag;
use App\Models\SubjectGroup;
use App\Models\SubjectGroupTag;

/**
 * Class CurriculumController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class CurriculumController extends Controller
{
    /**
     * Index.
     *
     * @param CurriculumIndex $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(CurriculumIndex $request)
    {
        $studies = map_collection(Study::with('family','department','tags','subjectGroups','subjectGroups.study','subjectGroups.tags')->get());
        $departments = map_collection(Department::all());
        $families = map_collection(Family::with('studies','studies.family')->get());
        $tags = map_collection(StudyTag::all());
        $subjectGroups = map_collection(SubjectGroup::with('study','tags')->get());
        $courses = map_collection(Course::with('study')->get());
        $subjectGroupTags = map_collection(SubjectGroupTag::all());
        return view('tenants.curriculum.index',
            compact('studies','departments','families','tags','subjectGroups','courses','subjectGroupTags'));
    }
}
