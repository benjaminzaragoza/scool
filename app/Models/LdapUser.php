<?php

namespace App\Models;

use App\Ldap\OpenLdapSchema;
use Cache;
use Illuminate\Database\Eloquent\Model;
use Adldap\Laravel\Facades\Adldap;

/**
 * Class LdapUser.
 *
 * @package App\Models
 */
class LdapUser extends Model
{
    const CACHE_KEY = 'ldap_users';

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


// TODO EXAMPLES
//        dd($users = Adldap::search()->users()->getUnescapedQuery());
// LIMITING         $users = Adldap::search()->users()->select(['*','createTimestamp','creatorsName','modifiersName','modifyTimestamp'])->limit($limit)->get();


    public static function createUser(User $user) {
        dump('createUser');
//        $ldapUser = Adldap::make()->setSchema(new OpenLdapSchema())->user([
////            'objectClass' => 'sambaSamAccount',
//            'cn' => 'Doe2',
//            'sn' => 'Doe2',
//            'uid' => 'foo1',
//            'uidNumber' => 9999455,
//            'gidNumber' => 9999455,
//            'homeDirectory' => '/home/asdasd',
//
//        ]);

//        $ldapUser->save();
        // ObjectClasses:
        // top
        // person
        // organizationalPerson
        // inetOrgPerson
//        dd(get_class(Adldap::make()));
        $ldapUser = Adldap::make()->setSchema(new OpenLdapSchema())->user([
            'objectclass' => [
                'posixaccount'
            ],
            'prova' => 'HEY',
            'cn' => 'PEPITOPALOTES1',
//            'sn' => 'PALOTES1'
        ]);


//        dump('createUser 2');

//        dd($ldapUser->getCreatableAttributes());
        if (!$ldapUser->save()) {
            dd('error');
        }
        dump('createUser 3');

        return $ldapUser;

        //// Creating a user:
//$user = Adldap::make()->user([
//'cn' => 'John Doe',
//]);
//
//// Modifying Attributes:
//$user->cn = 'Jane Doe';
//
//// Saving a user:
//
    }

    public static function getLdapUsers($limit = null)
    {
//        dd($users = Adldap::search()->users()->getUnescapedQuery());
        $cacheKey = tenant_from_current_url() . '_' . self::CACHE_KEY;

        return Cache::rememberForever($cacheKey, function() use ($limit) {
            $users = Adldap::search()->users()->select(['*','createTimestamp','creatorsName','modifiersName','modifyTimestamp'])->limit($limit)->get();
            return $users->map(function ($user) {
                return LdapUser::adapt($user);
            })->values();
        });
    }

    public static function adapt($user)
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
            'creatorsName' => $user->getAttribute('creatorsName')[0],
            'modifiersName' => $user->getAttribute('modifiersName')[0],
            'modifyTimestamp' => $user->getAttribute('modifyTimestamp')[0],
//            'jpegphoto' => $user->getAttribute('jpegphoto')[0], DOES NOT WORK -> ENCODING ERROR
            'jpegphoto' => $user->getJpegPhotoEncoded()
//            'attributes' => $user->getAttributes()
        ];
    }

    public static function findByEmail($email)
    {
        return Adldap::search()->where('email', '=', $email)->first();
    }

    public static function findByUid($uid)
    {
        return Adldap::search()->where('uid', '=', $uid)->first();
    }
}
