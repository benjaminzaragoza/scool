<?php

namespace App\Http\Controllers\Tenant\Api\Users;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUser;
use App\Models\User;

/**
 * Class UserEmailsController.
 *
 * @package App\Http\Controllers
 */
class UserEmailsController extends Controller
{

    protected $repository;

    /**
     * @param ShowUser $request
     * @param $tenant
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get(ShowUser $request, $tenant, $email)
    {
        return User::where('email',$email)->firstOrFail()->map();
    }


}
