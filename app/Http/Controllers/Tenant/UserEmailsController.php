<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\GetUser;
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
