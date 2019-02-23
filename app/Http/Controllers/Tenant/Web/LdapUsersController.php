<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Requests\Ldap\ListLdapUsers;
use App\Http\Controllers\Tenant\Controller;
use App\Models\LdapUser;
use App\Models\User;

/**
 * Class LdapUsersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class LdapUsersController extends Controller
{
    /**
     * Show.
     *
     * @param ListLdapUsers $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ListLdapUsers $request)
    {
        $users = LdapUser::getLdapUsers();
        $localUsers = map_collection(User::with(['roles','permissions','googleUser','person'])->get());
        $users = $users->map(function($user) use ($localUsers) {
            return LdapUser::adapt($user, $localUsers);
        });
        return view('tenants.ldap_users.index', compact('users','localUsers'));
    }
}
