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
        // TODO ELIMINAR
//        $users = LdapUser::getLdapUsers()->forPage(48, 25)->values();
//        dd($users->forPage(24, 50));
        $localUsers = map_collection(User::with(['roles','permissions','googleUser','person'])->get());
        // TODO ELIMINAR
//        dd(LdapUser::adapt($users[22], $localUsers));
//        dd($users[22]); // ou=All,dc=iesebre,dc=com" +"rdn": "cn=PEPITOPALOTES"

        $users = $users->map(function($user) use ($localUsers) {
            return LdapUser::adapt($user, $localUsers);
        });

        return view('tenants.ldap_users.index', compact('users','localUsers'));

    }
}
