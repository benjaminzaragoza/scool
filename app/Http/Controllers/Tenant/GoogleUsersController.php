<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Google\ListGoogleUsers;
use App\Models\GoogleUser;
use App\Models\User;

/**
 * Class GoogleUsersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class GoogleUsersController extends Controller
{
    /**
     * Show.
     *
     * @param ListGoogleUsers $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListGoogleUsers $request)
    {
        $users = GoogleUser::getGoogleUsers();
        $action = $request->action;

        $localUsers = map_collection(User::with(['roles','permissions','googleUser','person'])->get());

        $users = $users->map(function($user) use ($localUsers) {
            return GoogleUser::adapt($user, $localUsers);
        });

        return view('tenants.google_users.index', compact('users','localUsers','action'));
    }
}
