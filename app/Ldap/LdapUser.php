<?php

namespace App\Ldap;

/**
 * Class LdapUser.
 * @package App\Ldap
 */
class LdapUser
{
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