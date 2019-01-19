<?php

namespace App\Http\Controllers\Tenant\Api\Roles;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Roles\UserRoleDestroy;
use App\Http\Requests\Roles\UserRolesStore;
use App\Http\Requests\Roles\UserRoleStore;
use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * Class UserRoleController
 *
 * @package App\Http\Controllers
 */
class UserRoleController extends Controller
{

    /**
     * Store multiple.
     *
     * @param UserRolesStore $request
     * @param $tenant
     * @param User $user
     * @return int
     */
    public function storeMultiple(UserRolesStore $request, $tenant, User $user)
    {
        foreach ($request->roles as $role) {
            if (is_integer($role)) $user->assignRole(Role::findOrFail($role));
            else $user->assignRole(Role::where('name',$role)->first());
        }
        return count($request->roles);
    }
    /**
     * Store.
     *
     * @param UserRoleStore $request
     * @param $tenant
     * @param User $user
     * @param Role $role
     * @return User
     */
    public function store(UserRoleStore $request, $tenant, User $user, Role $role)
    {
        $user->assignRole($role);
        return $user;
    }

    /**
     * Destroy.
     *
     * @param UserRoleDestroy $request
     * @param $tenant
     * @param User $user
     * @param Role $role
     * @return User
     */
    public function destroy(UserRoleDestroy $request, $tenant, User $user, Role $role)
    {
        $user->removeRole($role);
        return $user;
    }
}
