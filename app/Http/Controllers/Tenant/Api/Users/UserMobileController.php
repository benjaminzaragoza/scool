<?php

namespace App\Http\Controllers\Tenant\Api\Users;

use App\Events\UserEmailUpdated;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUser;
use App\Http\Requests\Users\UpdateUser;
use App\Models\User;

/**
 * Class UserMobileController.
 *
 * @package App\Http\Controllers
 */
class UserMobileController extends Controller
{

    protected $repository;

    /**
     * @param ShowUser $request
     * @param $tenant
     * @param $email
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowUser $request, $tenant, $email)
    {
        return User::where('email',$email)->firstOrFail()->map();
    }

    /**
     * @param UpdateUser $request
     * @param $tenant
     * @param User $user
     * @return array
     */
    public function update(UpdateUser $request, $tenant, User $user)
    {
        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();
        event(new UserEmailUpdated($user));
        return $user->map();
    }

}
