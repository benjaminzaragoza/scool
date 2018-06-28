<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
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
     * Index.
     *
     * @param ListGoogleGroups $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListGoogleGroups $request)
    {
        $directory = new GoogleDirectory();
        //        config_google_api_for_tests();
        $groups = collect($directory->groups());
        return view('tenants.google_groups.show', compact('groups'));
    }

    public function store(StoreGoogleGroups $request)
    {
        dump('¡dsa');
//        GoogleGroupsController
    }
}
