<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\DestroyGoogleGroups;
use App\Http\Requests\ListGoogleGroups;
use App\Http\Requests\StoreGoogleGroups;

/**
 * Class GoogleGroupMembersController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class GoogleGroupMembersController extends Controller
{
    public function index(ListGoogleGroups $request, $tenant, $group)
    {
        $directory = new GoogleDirectory();
        return collect($directory->groupMembers($group));
    }
}
