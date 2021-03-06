<?php

namespace App\Http\Controllers\Tenant\Api\Ldap\Users;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Ldap\Users\UpdateLdapUserPassword;
use App\Models\LdapUser;

/**
 * Class LdapUsersPasswordController.
 *
 * @package App\Http\Controllers\Tenant
 */
class LdapUsersPasswordController extends Controller
{
    /**
     * update.
     *
     * @param UpdateLdapUserPassword $request
     * @return mixed
     */
    public function update(UpdateLdapUserPassword $request, $tenant, $user)
    {
        return LdapUser::changePassword($user,$request->password);
    }

}
