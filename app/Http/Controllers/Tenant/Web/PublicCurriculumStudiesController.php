<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;

use App\Models\Study;
use Illuminate\Http\Request;

/**
 * Class PublicCurriculumStudiesController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class PublicCurriculumStudiesController extends Controller
{
    /**
     * Index.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $tenant, Study $study)
    {
        $study = collect($study->mapForPublicView());
        return view('tenants.curriculum.public.studies.show', compact('study'));
    }
}
