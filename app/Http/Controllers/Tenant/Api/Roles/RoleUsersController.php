<?php

namespace App\Http\Controllers\Tenant\Api\Roles;

use App\Http\Controllers\Tenant\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * Class RoleUsersController
 *
 * @package App\Http\Controllers
 */
class RoleUsersController extends Controller
{
    /**
     * Index.
     *
     * @param Request $request
     * @param $tenant
     * @param Role $role
     * @return \Spatie\Permission\Contracts\Role|Role
     */
    public function index(Request $request, $tenant, Role $role)
    {
        return User::role($role)->get();
    }

}
