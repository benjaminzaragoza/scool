<?php

namespace App\Models;

use App\Ldap\OpenLdapSchema;
use Cache;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Str;

/**
 * Class LdapUser.
 *
 * @package App\Models
 */
class LdapUser extends Model
{
    const CACHE_KEY = 'ldap_users';
    const EMPLOYEE_NUMBER_CAN_BE_SYNCED = 1;
    const EMAIL_CAN_BE_SYNCED = 2;

    protected $guarded = [];

    private static function passwordType($password)
    {
        if (Str::startsWith($password, '{MD5}')) return 'MD5';
        if (Str::startsWith($password, '{SSHA}')) return 'SSHA';
        if (Str::startsWith($password, '{SHA1}')) return 'SHA1';
        return '';
    }

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
                return LdapUser::map($user);
            })->values();
        });
    }

    public static function map($user)
    {
        $base_dn = config('ldap.connections.default.settings.base_dn');
        $ldap_base = config('scool.ldap_base');
        $dn = $user->getAttribute('dn')[0];
        $rdn = Str::replaceLast(',','',Str::replaceFirst($base_dn, '', $dn));
        $sambasid = $user->getAttribute('sambasid')[0];
        $sambarid = Str::replaceFirst('-','',Str::replaceFirst(config('samba.sid'),'',$sambasid));

        $modifyTimestamp = $user->getAttribute('modifyTimestamp')[0];
        $modifyTimestampDateTime = DateTime::createFromFormat( 'YmdHisZ', $modifyTimestamp);
        $modifyFormatted = $modifyTimestampDateTime->format('H:i:s d-m-Y');
        Carbon::setLocale(config('app.locale'));
        $modifyHuman = (new Carbon($modifyTimestampDateTime->format(DATE_ISO8601)))->diffForHumans(Carbon::now());

        $createTimestamp = $user->getAttribute('createTimestamp')[0];
        $createTimestampDateTime = DateTime::createFromFormat( 'YmdHisZ', $createTimestamp);
        $createFormatted = $createTimestampDateTime->format('H:i:s d-m-Y');
        Carbon::setLocale(config('app.locale'));
        $createHuman = (new Carbon($createTimestampDateTime->format(DATE_ISO8601)))->diffForHumans(Carbon::now());
        $creatorsName = $user->getAttribute('creatorsName')[0];
        $creatorsNameRDN = Str::replaceLast(',','',Str::replaceFirst($ldap_base, '', $creatorsName));
        $modifiersName = $user->getAttribute('modifiersName')[0];
        $modifiersNameRDN = Str::replaceLast(',','',Str::replaceFirst($ldap_base, '', $modifiersName));

        $password = $user->getAttribute('userpassword')[0];
        $passwordType = self::passwordType($password);
        return (object)[
            'objectClass' => $user->objectclass,
            'base_dn' => $base_dn,
            'rdn' => $rdn,
            'cn' => $user->getAttribute('cn')[0],
            'dn' => $dn,
            'uid' => $user->getAttribute('uid')[0],
            'userpassword' => $password,
            'passwordtype' => $passwordType,
            'uidnumber' => $user->getAttribute('uidnumber')[0],
            'gidnumber' => $user->getAttribute('gidnumber')[0],
            'homedirectory' => $user->getAttribute('homedirectory')[0],

            'sambasid' => $sambasid,
            'sambarid' => $sambarid,

            'givenname' => $user->getAttribute('givenname')[0],
            'sn' => $user->getAttribute('sn')[0],
            'sn1' => $user->getAttribute('sn1')[0],
            'sn2' => $user->getAttribute('sn2')[0],
            'irispersonaluniqueid' => $user->getAttribute('irispersonaluniqueid')[0],
            'highschooluserid' => $user->getAttribute('highschooluserid')[0],
            'highschoolpersonalemail' => $user->getAttribute('highschoolpersonalemail')[0],
            'email' => $user->getAttribute('email')[0],

            'employeetype' => $user->getAttribute('employeetype')[0],
            'employeenumber' => $user->getAttribute('employeenumber')[0],
            'l' => $user->getAttribute('l')[0],
            'st' => $user->getAttribute('st')[0],
            'telephonenumber' => $user->getAttribute('telephonenumber')[0],
            'mobile' => $user->getAttribute('mobile')[0],
            'postalCode' => $user->getAttribute('postalCode')[0],
            'createtimestamp' => $user->getAttribute('createtimestamp')[0],
            'creatorsName' => $creatorsName,
            'creatorsNameRDN' => $creatorsNameRDN,
            'modifiersName' => $modifiersName,
            'modifiersNameRDN' => $modifiersNameRDN,
            'modifyTimestamp' => $modifyTimestamp,
            'modifyFormatted' => $modifyFormatted,
            'modifyHuman' => $modifyHuman,
            'createTimestamp' => $createTimestamp,
            'createFormatted' => $createFormatted,
            'createHuman' => $createHuman,
//            'jpegphoto' => $user->getAttribute('jpegphoto')[0], DOES NOT WORK -> ENCODING ERROR
            'jpegphoto' => $user->getJpegPhotoEncoded()
//            'attributes' => $user->getAttributes()
        ];
    }

    public static function findByDn($dn)
    {
        return Adldap::search()->select(['*','createTimestamp','creatorsName','modifiersName','modifyTimestamp'])->
            findByDn($dn);
    }

    public static function findByEmail($email)
    {
        return Adldap::search()->select(['*','createTimestamp','creatorsName','modifiersName','modifyTimestamp'])->where('email', '=', $email)->first();
    }

    public static function findByUid($uid)
    {
        return Adldap::search()->select(['*','createTimestamp','creatorsName','modifiersName','modifyTimestamp'])->where('uid', '=', $uid)->first();
    }

    public static function initializeUser($user)
    {
        $user->errorMessages = collect([]);
        $user->inSync = false;
        $user->flags = collect([]);
        return $user;
    }

    /**
     * addLocalUser.
     *
     * @param $user
     * @param $scoolUser
     * @return mixed
     */
    public static function addLocalUser($user,$scoolUser)
    {
        if ($scoolUser) {
            $user->localUser = $scoolUser;
        }
        $user = self::userInSync($user, $scoolUser);
        return $user;
    }

    public static function adapt($user, $localUsers)
    {
        $user = LdapUser::initializeUser($user);
        if (isset($user->dn)) {
            $user = self::addLocalUser($user, self::findByDn($user->dn));
            return $user;
        }
//        if (isset($user->primaryEmail) && $user->primaryEmail ) {
//            $user = self::addLocalUser($user, self::findByPrimaryEmail($localUsers, $user));
//            return $user;
//        }
//        if (isset($user->personalEmail) && $user->personalEmail ) {
//            $user = self::addLocalUser($user, self::findByPersonalEmail($localUsers, $user));
//            return $user;
//        }
    }

    /**
     * userInSync.
     *
     * @param $user
     * @param $scoolUser
     * @return mixed
     */
    public static function userInSync($user,$scoolUser)
    {
        $errors = false;
        if (intval($user->employeenumber) !== intval($scoolUser['id'])) {
            $user->errorMessages->push("Employeenumber no vàlid. No hi ha cap usuari local amb aquest id");
            $user->flags->push(LdapUser::EMPLOYEE_NUMBER_CAN_BE_SYNCED);
            $errors = true;
        }
        if (!$errors) $user->inSync = true;
        if ($user->email !== $scoolUser['email']) {
            $user->errorMessages->push('Email no vàlid. No hi ha cap usuari local amb aquest email personal');
            $user->flags->push(GoogleUser::EMAIL_CAN_BE_SYNCED);
            $errors = true;
        }
        return $user;
    }

}
