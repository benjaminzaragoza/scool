<?php

namespace App\Http\Controllers\Tenant\Api\Ldap;

use Adldap\Laravel\Facades\Adldap;
use App\Http\Requests\ListLdapUsers;
use App\Http\Requests\StoreLdapUsers;
use App\Ldap\OpenLdapSchema;
use App\Http\Controllers\Tenant\Controller;

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
     */
    public function index(ListLdapUsers $request)
    {
        return LdapUser::getLdapUsers();
    }

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
