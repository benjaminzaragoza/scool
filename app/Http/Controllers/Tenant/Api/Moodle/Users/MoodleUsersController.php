<?php

namespace App\Http\Controllers\Tenant\Api\Moodle\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Moodle\Users\MoodleUserIndex;
use App\Moodle\Entities\MoodleUser;

/**
 * Class MoodleUsersController.
 *
 * @package App\Http\Controllers\Api
 */
class MoodleUsersController extends Controller
{
    /**
     * Index.
     *
     * @param MoodleUserIndex $request
     * @return mixed
     */
    public function index(MoodleUserIndex $request)
    {
        return MoodleUser::all();
    }

//    public function store(StoreIncident $request)
//    {
////        $incident = Incident::create($request->only('subject','description'))->assignUser($request->user());
////        event(new IncidentStored($incident));
////        return $incident->load(['user'])->map();
//    }
//
//    public function show(ShowIncident $request, $tenant,Incident $incident)
//    {
////        event(new IncidentShowed($incident));
////        return $incident->map();
//    }
//
//
//    public function destroy(DeleteIncident $request, $tenant, Incident $incident)
//    {
////        $oldIncident = clone($incident);
////        $incident->delete();
////        event(new IncidentDeleted($oldIncident));
////        return $incident;
//    }
}
