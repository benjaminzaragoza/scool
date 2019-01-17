<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUsersManagement;
use Spatie\Permission\Models\Role;

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
        $roles = map_collection(Role::all());
        return view('tenants.users.roles.index',compact('roles'));
    }

}
