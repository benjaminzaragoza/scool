<?php

namespace App\Http\Controllers\Tenant\Api\Users;

use App\Events\UserMobileUpdated;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\ShowUser;
use App\Http\Requests\Users\UpdateUserMobile;
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
     * @param $mobile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowUser $request, $tenant, $mobile)
    {
        return User::where('mobile',$mobile)->firstOrFail()->map();
    }

    /**
     * @param UpdateUserMobile $request
     * @param $tenant
     * @param User $user
     * @return array
     */
    public function update(UpdateUserMobile $request, $tenant, User $user)
    {
        $user->mobile = $request->mobile;
        $user->mobile_verified_at = null;
        $user->save();
        event(new UserMobileUpdated($user));
        return $user->map();
    }

}
