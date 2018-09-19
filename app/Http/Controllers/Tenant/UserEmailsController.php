<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\AddUser;
use App\Http\Requests\DeleteUser;
use App\Http\Requests\GetUser;
use App\Http\Requests\ShowUsersManagement;
use App\Http\Resources\Tenant\UserCollection;
use App\Http\Resources\Tenant\UserResource;
use App\Http\Resources\Tenant\UserTypesCollection;
use App\Models\User;
use App\Models\UserType;
use App\Repositories\UserRepository;
use Spatie\Permission\Models\Role;

/**
 * Class UseremailsController.
 *
 * @package App\Http\Controllers
 */
class UseremailsController extends Controller
{

    protected $repository;

    /**
     * UseremailsController constructor.
     * @param $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param GetUser $request
     * @param $tenant
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get(GetUser $request, $tenant, $email)
    {
        return User::where('email',$email)->firstOrFail()->map();
    }


}
