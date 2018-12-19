<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\CurriculumIndex;
use App\Models\Department;
use App\Models\Family;
use App\Models\Study;
use App\Models\StudyTag;

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

        $studies = map_collection(Study::all());
        $departments = map_collection(Department::all());
        $families = map_collection(Family::all());
        $tags = map_collection(StudyTag::all());
        return view('tenants.curriculum.index', compact('studies','departments','families','tags'));
    }
}
