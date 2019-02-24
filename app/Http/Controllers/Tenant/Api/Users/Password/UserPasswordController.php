<?php

namespace App\Http\Controllers\Tenant\Api\Users\Password;

use App\Events\Users\Password\UserPasswordChangedByManager;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Users\Password\UserPasswordUpdate;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

/**
 * Class UserPasswordController.
 *
 * @package App\Http\Controllers
 */
class UserPasswordController extends Controller
{
    /**
     * Update user.
     *
     * @param Request $request
     * @return \App\User|null
     */
    public function update(UserPasswordUpdate $request, $tenant, User $user)
    {
        $user->password = $request->password;
        $user->save();
        $options = [];
        if ($request->options) $options = $request->options;
        event(new UserPasswordChangedByManager($user,$request->password, $options));
        return $user->mapSimple();
    }
}
