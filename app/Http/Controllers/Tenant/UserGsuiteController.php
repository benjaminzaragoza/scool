<?php

namespace App\Http\Controllers\Tenant;


use App\Http\Requests\AddUser;
use App\Http\Requests\AssociateGsuiteUserToUser;
use App\Http\Requests\GetUser;
use App\Models\User;

/**
 * Class UserGsuiteController.
 *
 * @package App\Http\Controllers
 */
class UserGsuiteController extends Controller
{

    protected $repository;

    /**
     * @param GetUser $request
     * @param $tenant
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(AssociateGsuiteUserToUser $request, $tenant, $email)
    {

    }


}
