<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Family;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Setting;
use App\Models\Specialty;
use App\Revisionable\Revision;
use Auth;
use Config;
use Illuminate\Http\Request;
use App\Http\Resources\Tenant\Revision as RevisionResource;

/**
 * Class HomeController.
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show(Request $request)
    {
        return view('tenants.home', [ 'user' => Auth::user()]);
    }

}
