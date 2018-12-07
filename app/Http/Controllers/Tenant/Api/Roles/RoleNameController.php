<?php

namespace App\Http\Controllers\Tenant\Api\Roles;

use App\Http\Controllers\Tenant\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

/**
 * Class RoleNameController
 *
 * @package App\Http\Controllers
 */
class RoleNameController extends Controller
{
    /**
     * Show.
     *
     * @param Request $request
     * @param $tenant
     * @param $name
     * @return \Spatie\Permission\Contracts\Role|Role
     */
    public function show(Request $request, $tenant, $name)
    {
        return Role::findByName($name);
    }

}
