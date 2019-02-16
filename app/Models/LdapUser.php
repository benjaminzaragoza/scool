<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Adldap\Laravel\Facades\Adldap;

/**
 * Class LdapUser.
 *
 * @package App\Models
 */
class LdapUser extends Model
{
    protected $guarded = [];

    /**
     * Get the local user associated to ldap user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

//    TODO ELIMINAR EXAMPLES
// https://adldap2.github.io/Adldap2-Laravel/#/
// Finding a user:
//$user = Adldap::search()->users()->find('john doe');
//
//// Searching for a user:
//$search = Adldap::search()->where('cn', '=', 'John Doe')->get();
//
//// Running an operation under a different connection:
//$users = Adldap::getProvider('other-connection')->search()->users()->get();
//
//// Creating a user:
//$user = Adldap::make()->user([
//'cn' => 'John Doe',
//]);
//
//// Modifying Attributes:
//$user->cn = 'Jane Doe';
//
//// Saving a user:
//$user->save();


    public static function getLdapUsers($limit = null)
    {
//        dd(get_class($users = Adldap::search()));
//        dd($users = Adldap::search()->users()->getUnescapedQuery());
        //TODO
        $limit = 100;
        $users = Adldap::search()->users()->select(['*','createTimestamp','creatorsName','modifiersName','modifyTimestamp'])->limit($limit)->get();
//        dd($users[0]);
//        $users = Adldap::search()->users()->limit(10)->get()->take($limit);
//        dd($users[0]);
//        dd($users);
//        return $users->values();
        return $users->map(function ($user) {
            return LdapUser::convert($user);
        })->values();
//        return Cache::rememberForever('ldap_users', function() use ($users){
//            return $users->map(function ($user) {
//                return LdapUser::convert($user);
//            })->values();
//        });
    }

    public static function convert($user)
    {
        return (object)[
            'cn' => $user->getAttribute('cn')[0],
            'dn' => $user->getAttribute('dn')[0],
            'uid' => $user->getAttribute('uid')[0],
            'userpassword' => $user->getAttribute('userpassword')[0],
            'uidnumber' => $user->getAttribute('uidnumber')[0],
            'givenname' => $user->getAttribute('givenname')[0],
            'sn' => $user->getAttribute('sn')[0],
            'sn1' => $user->getAttribute('sn1')[0],
            'sn2' => $user->getAttribute('sn2')[0],
            'irispersonaluniqueid' => $user->getAttribute('irispersonaluniqueid')[0],
            'employeetype' => $user->getAttribute('employeetype')[0],
            'l' => $user->getAttribute('l')[0],
            'st' => $user->getAttribute('st')[0],
            'telephonenumber' => $user->getAttribute('telephonenumber')[0],
            'mobile' => $user->getAttribute('mobile')[0],
            'postalCode' => $user->getAttribute('postalCode')[0],
            'createtimestamp' => $user->getAttribute('createtimestamp')[0],
//            'attributes' => $user->getAttributes()
        ];
    }
}
