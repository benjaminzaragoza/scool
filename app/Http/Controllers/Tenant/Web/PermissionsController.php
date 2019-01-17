<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUsersManagement;
use App\Models\Permission;

/**
 * Class PermissionsController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class PermissionsController extends Controller
{
    /**
     * @param ShowUsersManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ShowUsersManagement $request)
    {
        $permissions = map_collection(Permission::all());
        return view('tenants.users.permissions.index',compact('permissions'));
    }

}
