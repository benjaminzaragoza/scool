<?php

namespace App\Http\Controllers\Tenant;

use Adldap\Laravel\Facades\Adldap;
use App\Http\Requests\ListLdapUsers;
use Adldap\AdldapInterface;
use App\Http\Requests\StoreLdapUsers;
use App\Ldap\LdapUser;
use App\Ldap\OpenLdapSchema;

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
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }

    /**
     * Index.
     */
    public function index(ListLdapUsers $request)
    {
        return $this->getLdapUsers();
    }

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

    private function getLdapUsers()
    {
//        dd(get_class($this->ldap));
//        dd(get_class($users = $this->ldap->search()));
//        dd($users = $this->ldap->search()->users()->getUnescapedQuery());
        $users = $this->ldap->search()->users()->get();
//        dd($users);
        $users = $this->ldap->search()->users()->select(['*','createTimestamp','creatorsName','modifiersName','modifyTimestamp'])->limit(100)->get();
//        dd($users);
//        dd($users[10]);
        return $users->map(function ($user) {
            return LdapUser::convert($user);
        })->values();
//        return Cache::rememberForever('ldap_users', function() use ($users){
//            return $users->map(function ($user) {
//                return LdapUser::convert($user);
//            })->values();
//        });
    }

}
