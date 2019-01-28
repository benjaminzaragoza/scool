<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\DestroyGoogleUsers;
use App\Http\Requests\ListGoogleUsers;
use App\Http\Requests\StoreGoogleUsers;
use App\Models\GoogleUser;
use App\Models\User;
use Cache;
use Google_Service_Exception;

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

        return view('tenants.google_users.show', compact('users','action'));
    }

    public function show()
    {
        dd('TODO');
    }
}
