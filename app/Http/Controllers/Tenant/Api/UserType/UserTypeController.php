<?php

namespace App\Http\Controllers\Tenant\Api\UserType;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\UserType\StoreUserType;


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
}
