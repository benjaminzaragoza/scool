<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\DestroyGoogleGroups;
use App\Http\Requests\ListGoogleGroups;
use App\Http\Requests\StoreGoogleGroups;

/**
 * Class GoogleGroupsController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class GoogleGroupsController extends Controller
{
    /**
     * Show.
     *
     * @param ListGoogleGroups $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ListGoogleGroups $request)
    {
        $directory = new GoogleDirectory();
        $groups = collect($directory->groups());
        return view('tenants.google_groups.show', compact('groups'));
    }

    /**
     * Index.
     *
     * @param ListGoogleGroups $request
     * @return \Illuminate\Support\Collection
     */
    public function index(ListGoogleGroups $request)
    {
        $directory = new GoogleDirectory();
        return collect($directory->groups());
    }

    /**
     * Store.
     *
     * @param StoreGoogleGroups $request
     */
    public function store(StoreGoogleGroups $request)
    {
        if (google_group_exists($request->email)) abort('422','Already exists');
        $directory = new GoogleDirectory();
        try {
            $directory->group([
                'name' => $request->name,
                'email' => $request->email,
                'description' => $request->description,
            ]);
        } catch (Google_Service_Exception $e) {
            abort('422',$e);
        }
    }

    public function destroy(DestroyGoogleGroups $request, $tenant, $group)
    {
        dump('GROUP: ' . $group);
        dump('TODO Destroy');
        try {
            (new GoogleDirectory())->removeGroup($group);
        } catch (Google_Service_Exception $e) {
            dd('TODO');
        }
    }
}
