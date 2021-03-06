<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
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
        $families = map_collection(Family::orderBy('name')->get());
        return view('tenants.curriculum.public.index', compact('families'));
    }
}
