<?php

namespace App\Http\Controllers\Tenant\Api\Roles;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUsersManagement;
use App\Models\Role;

/**
 * Class RolesController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class RolesController extends Controller
{
    /**
     * @param ShowUsersManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ShowUsersManagement $request)
    {
        return map_collection(Role::all());
    }
}
