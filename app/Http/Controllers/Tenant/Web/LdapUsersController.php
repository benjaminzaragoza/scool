<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Requests\ListLdapUsers;
use App\Http\Controllers\Tenant\Controller;
use App\Models\LdapUser;

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
        return view('tenants.ldap_users.index', compact('users'));
    }
}
