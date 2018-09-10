<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\DestroyGoogleUsers;
use App\Http\Requests\ListGoogleUsers;
use App\Http\Requests\ListLdapUsers;
use App\Http\Requests\StoreGoogleUsers;
use Cache;
use Google_Service_Exception;

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
     * @param ListGoogleUsers $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ListLdapUsers $request)
    {
        $users = $this->getLdapUsers();
        return view('tenants.ldap_users.show', compact('users'));
    }

    private function getLdapUsers()
    {
        return collect([]);
    }


}
