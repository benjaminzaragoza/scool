<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteJob;
use App\Http\Requests\ListJobs;
use App\Http\Requests\ShowJobsManagement;
use App\Http\Requests\StoreJob;
use App\Http\Requests\UpdateJob;
use App\Models\Employee;
use App\Models\Family;
use App\Models\Specialty;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use App\Http\Resources\Tenant\Job as JobResource;

/**
 * Class JobsController.
 *
 * @package App\Http\Controllers\Tenant
 */
class JobsController extends Controller
{

    /**
     * Index.
     *
     * @param ListJobs $request
     * @return \Illuminate\Support\Collection
     */
    public function index(ListJobs $request)
    {
        return $this->jobs();
    }

    protected function jobs() {
        return collect(JobResource::collection(
            Job::with(
                'type',
                'family',
                'specialty',
                'users',
                'holders',
                'holders.teacher',
                'substitutes',
                'substitutes.teacher')->get()));
    }

    /**
     * Show staff management.
     *
     * @param ShowJobsManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowJobsManagement $request)
    {
        $jobs =  $this->jobs();

        $jobTypes = JobType::all();
        $specialties = Specialty::with('jobs','jobs.family')->get();
        $families = Family::with('jobs','jobs.specialty')->get();
        $users = User::available();
        $firstAvailableCode = Job::firstAvailableCode();

        return view('tenants.jobs.show',compact(
            'jobs',
            'jobTypes',
            'specialties',
            'families',
            'users',
            'firstAvailableCode'
        ));
    }

    /**
     * Store.
     *
     * @return string
     */
    public function store(StoreJob $request)
    {
        $job = Job::create([
            'code' => $request->code,
            'type_id' => $request->type,
            'specialty_id' => $request->specialty,
            'family_id' => $request->family,
            'order' => $request->order,
            'notes' => $request->notes
        ])->load('type','specialty','family','users');

        if ($request->holder) $job->users()->save(User::findOrFail($request->holder), ['holder' => 1]);
        return $job;
    }

    /**
     * Update.
     *
     * @return string
     */
    public function update(UpdateJob $request, $tenant, Job $job)
    {
        $job->code = $request->code;
        $job->type_id = $request->type;
        $job->specialty_id = $request->specialty;
        $job->family_id = $request->family;
        $job->order = $request->order;
        $request->notes && $job->notes = $request->notes;

        $job->save();

        if ($request->holder) {
            $employee = Employee::where('holder',1)->where('job_id',$job->id)->first();
            if($employee) {
                $employee->user_id = $request->holder;
                $employee->save();
            } else {
                Employee::create([
                    'user_id' => $request->holder,
                    'job_id' => $job->id,
                    'holder' => 1
                ]);
            }

        }
        return $job;
    }

    /**
     * Destroy
     *
     * @param DeleteJob $request
     * @param $tenant
     * @param Job $job
     * @return Job
     * @throws \Exception
     */
    public function destroy(DeleteJob $request, $tenant, Job $job)
    {
        $job->delete();
        return $job;
    }

    /**
     * Next available code.
     *
     * @param ShowJobsManagement $request
     * @return string
     */
    public function nextAvailableCode(ShowJobsManagement $request)
    {
        return Job::firstAvailableCode();
    }
}
