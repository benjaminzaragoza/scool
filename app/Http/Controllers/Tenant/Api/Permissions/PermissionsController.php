<?php

namespace App\Http\Controllers\Tenant\Api\Permissions;

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
        return map_collection(Permission::all());
    }
}
