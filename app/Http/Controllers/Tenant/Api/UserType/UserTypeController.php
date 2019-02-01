<?php

namespace App\Http\Controllers\Tenant\Api\UserType;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\UserType\DestroyUserType;
use App\Http\Requests\UserType\StoreUserType;
use App\Http\Requests\UserType\UpdateUserType;
use App\Models\User;
use App\Models\UserType;


/**
 * Class UserTypeController
 * @package App\Http\Controllers\Tenant\Api\Changelog
 */
class UserTypeController extends Controller
{
    /**
     * Store.
     *
     * @param StoreUserType $request
     * @return mixed
     */
    public function store(StoreUserType $request)
    {
        $user = $request->user();
        $user->user_type_id = $request->type;
        $user->save();
        return $user;
    }

    /**
     * update.
     *
     * @param UpdateUserType $request
     * @param $tenant
     * @param User $user
     * @param UserType $userType
     * @return User
     */
    public function update(UpdateUserType $request, $tenant, User $user, UserType $userType)
    {
        $user->user_type_id = $userType->id;
        $user->save();
        return $user;
    }

    /**
     * destroy.
     *
     * @param DestroyUserType $request
     * @param $tenant
     * @param User $user
     * @return User
     */
    public function destroy(DestroyUserType $request, $tenant, User $user)
    {
        $user->user_type_id = null;
        $user->save();
        return $user;
    }
}
