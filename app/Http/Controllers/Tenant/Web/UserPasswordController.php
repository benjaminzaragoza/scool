<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUsersManagement;
use App\Http\Resources\Tenant\UserTypesCollection;
use App\Models\User;
use App\Models\UserType;
use Spatie\Permission\Models\Role;

/**
 * Class UserPasswordController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class UserPasswordController extends Controller
{
    /**
     * show
     * @param ShowUsersManagement $request
     * @param $tenant
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowUsersManagement $request, $tenant, User $user)
    {
        $users = User::getUsers();
        $userTypes = (new UserTypesCollection(UserType::with('roles')->get()))->transform();
        $roles = Role::all();
        return view('tenants.user.password.show',compact('users','user','userTypes','roles'));
    }
}
