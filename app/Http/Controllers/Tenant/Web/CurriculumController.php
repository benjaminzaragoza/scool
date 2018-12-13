<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\CurriculumIndex;


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

        $studies = collect([]);
        return view('tenants.curriculum.index', compact('studies'));
    }
}
