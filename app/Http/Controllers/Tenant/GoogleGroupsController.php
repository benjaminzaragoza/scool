<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\ListGoogleGroups;

/**
 * Class GoogleGroupsController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class GoogleGroupsController extends Controller
{

    public $directory;

    /**
     * GoogleGroupsController constructor.
     *
     * @param $directory
     */
    public function __construct(GoogleDirectory $directory)
    {
        $this->directory = $directory;
    }

    /**
     * Index.
     *
     * @param ListGoogleGroups $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListGoogleGroups $request)
    {
        $groups = $this->directory->groups();
        return view('tenants.google_groups.show', compact('groups'));
    }
}
