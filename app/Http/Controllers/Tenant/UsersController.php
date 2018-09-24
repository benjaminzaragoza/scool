<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\AddUser;
use App\Http\Requests\DeleteUser;
use App\Http\Requests\GetUser;
use App\Http\Requests\ShowUsersManagement;
use App\Http\Resources\Tenant\UserResource;
use App\Http\Resources\Tenant\UserTypesCollection;
use App\Models\User;
use App\Models\UserType;
use App\Repositories\UserRepository;
use Spatie\Permission\Models\Role;

/**
 * Class UsersController.
 *
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

    protected $repository;

    /**
     * UsersController constructor.
     * @param $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param ShowUsersManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowUsersManagement $request)
    {
        $users = User::getUsers();
        $userTypes = (new UserTypesCollection(UserType::with('roles')->get()))->transform();
        $roles = Role::all()->pluck('name');
        return view('tenants.users.show',compact('users','userTypes','roles'));
    }

    /**
     * @param GetUser $request
     * @param $tenant
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get(GetUser $request, $tenant, User $user)
    {
        return $user->map();
    }

    /**
     * Show users.
     *
     * @param ShowUsersManagement $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(ShowUsersManagement $request)
    {
        return User::getUsers();
    }

    /**
     * Store user on database.
     *
     * @param AddUser $request
     * @return mixed
     */
    public function store(AddUser $request)
    {
        $user = $this->repository->store($request);

        if ($request->roles) {
            foreach ( $request->roles as $role) {
                $user->assignRole(Role::findByName($role,'web'));
            }
        }

        return new UserResource($user);
    }

    /**
     * Store user on database and invite.
     *
     * @param AddUser $request
     * @return mixed
     */
    public function store_and_invite(AddUser $request)
    {
        // TODO send invitation
        return $this->repository->store($request);
    }

    /**
     * Delete user.
     *
     * @param DeleteUser $request
     * @param $tenant
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function destroy(DeleteUser $request, $tenant, User $user)
    {
        $user->delete();
        return $user;
    }
}
