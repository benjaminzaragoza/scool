<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\CurriculumIndex;
use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\StudyTag;
use Illuminate\Http\Request;

/**
 * Class PublicCurriculumController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class PublicCurriculumController extends Controller
{
    /**
     * Index.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $studies = map_collection(Study::with('family','department','tags','subjectGroups','subjectGroups.study','subjectGroups.tags')->get());
        $departments = map_collection(Department::all());
        $families = map_collection(Family::orderBy('name')->get());
        $tags = map_collection(StudyTag::all());
        return view('tenants.curriculum.public.index', compact('studies','departments','families','tags'));
    }
}
