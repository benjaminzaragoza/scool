<?php

namespace App\Http\Controllers\Tenant\Api\Ldap\Users;

use Adldap\Laravel\Facades\Adldap;
use App\Http\Requests\Ldap\Users\ListLdapUsers;
use App\Http\Requests\Ldap\Users\StoreLdapUsers;
use App\Ldap\OpenLdapSchema;
use App\Http\Controllers\Tenant\Controller;
use App\Models\LdapUser;
use Cache;

/**
 * Class LdapUsersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class LdapUsersController extends Controller
{

    /**
     * @var Adldap
     */
    protected $ldap;

    /**
     * Index.
     *
     * @param ListLdapUsers $request
     * @return mixed
     */
    public function index(ListLdapUsers $request)
    {
        if (!$request->cache) Cache::forget(tenant_from_current_url() . '_' . LdapUser::CACHE_KEY);
        return LdapUser::getLdapUsers();
    }

    /**
     * Store.
     *
     * @param StoreLdapUsers $request
     */
    public function store(StoreLdapUsers $request)
    {
        // Creating a user:
//        dd(get_class(Adldap::make()));
        $user = Adldap::make()->setSchema(new OpenLdapSchema())->user([
            'objectClass' => 'sambaSamAccount',
            'cn' => 'Doe2',
            'sn' => 'Doe2',
            'uid' => 'foo1',
            'uidNumber' => 9999455,
            'gidNumber' => 9999455,
            'homeDirectory' => '/home/asdasd',

        ]);

        $user->save();
    }

}
