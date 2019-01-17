<?php

namespace App\Http\Controllers\Tenant\Api\Users;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\AddUser;
use App\Http\Requests\Users\DeleteMultipleUsers;
use App\Http\Requests\Users\ListUsersManagement;
use App\Http\Requests\Users\DeleteUser;
use App\Http\Requests\Users\ShowUser;
use App\Models\User;
use App\Repositories\UserRepository;
use Spatie\Permission\Models\Role;

/**
 * Class UsersController.
 *
 * @package App\Http\Controllers\Tenant\Api\Users
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
     * @param ShowUser $request
     * @param $tenant
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowUser $request, $tenant, User $user)
    {
        return $user->map();
    }

    /**
     * Show users.
     *
     * @param ListUsersManagement $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(ListUsersManagement $request)
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

        return $user->map();
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

    public function destroyMultiple(DeleteMultipleUsers $request)
    {
        $result = User::destroy($request->users);
        return $result;
    }

}
