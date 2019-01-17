<?php

namespace App\Http\Controllers\Tenant\Api\Users;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUser;
use App\Models\User;

/**
 * Class UserNamesController.
 *
 * @package App\Http\Controllers
 */
class UserNamesController extends Controller
{

    protected $repository;

    /**
     * @param ShowUser $request
     * @param $tenant
     * @param $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowUser $request, $tenant, $name)
    {
        return User::where('name',$name)->firstOrFail()->map();
    }


}
