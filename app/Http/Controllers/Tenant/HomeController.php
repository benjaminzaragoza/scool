<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Family;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Setting;
use App\Models\Specialty;
use App\Revisionable\Revision;
use Auth;
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
        $teacherTotals = collect([
            'Total',
            'Reals'
        ]);
        $teacherTotalsData = collect([
            Job::where('type_id',JobType::findByName('Professor/a')->id)->count(),
            110
        ]);
        $teacherTotalsColors = collect(['#F7464A','#46BFBD']);
        $tipusJornades = collect([
            'Completa',
            'Mitja',
            '1/3'
        ]); // TODO

        $jobTypes = JobType::all();
        $teacherTypes = JobType::all()->pluck('name');

        foreach ($jobTypes as $jobType) {
            $teacherTypesData[] = Job::where('type_id',$jobType->id)->count();
        }

        $teacherTypesData = collect($teacherTypesData);

        foreach (Family::all() as $family) {
            $families[$family->code] = Job::where('family_id',$family->id)->count();
        }
        $families = collect($families)->sortKeys();

        foreach (Specialty::all() as $specialty) {
            $specialties[$specialty->code] = Job::where('specialty_id',$specialty->id)->count();
        }
        $specialties = collect($specialties)->sortKeys();

        $auditLogItems = $this->auditLogs();
        return view('tenants.home',compact('auditLogItems',
            'teacherTotals',
            'teacherTotalsData',
            'teacherTotalsColors',
            'teacherTypes',
            'teacherTypesData',
            'families',
            'specialties',
            'tipusJornades'
            ));
    }

    /**
     * Audit logs.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function auditLogs() {
        return collect(RevisionResource::collection(
            Revision::orderBy('created_at', 'desc')->with(['user','revisionable'])->get()));
    }
}
