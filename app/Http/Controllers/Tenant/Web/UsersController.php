<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUsersManagement;
use App\Http\Resources\Tenant\UserTypesCollection;
use App\Models\User;
use App\Models\UserType;
use Spatie\Permission\Models\Role;

/**
 * Class UsersController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class UsersController extends Controller
{
    /**
     * @param ShowUsersManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ShowUsersManagement $request)
    {
        $users = User::getUsers();
        $userTypes = (new UserTypesCollection(UserType::with('roles')->get()))->transform();
        $roles = Role::all();
        return view('tenants.users.show',compact('users','userTypes','roles'));
    }

}
