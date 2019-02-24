<?php

namespace App\Models;

use Adldap\Models\ModelNotFoundException;
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
    const UID_CAN_BE_SYNCED = 2;

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
            $result = $users->map(function ($user) {
                return LdapUser::map($user);
            })->values();
            return $result->filter(function ($value) {
                return $value !== null;
            });
        });
    }

    public static function fullSearch($user)
    {
        return $user->cn[0] . ' ' . $user->email[0] . ' ' . $user->uid[0] . ' ' . $user->givenname[0] . ' ' .
            $user->sn1[0] . ' ' . $user->sn2[0] . ' ' . $user->irispersonaluniqueid[0] .
            ' ' . $user->employeenumber[0] ;
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

        $sambapwdlastset = $user->getAttribute('sambapwdlastset')[0];
        $carbonsambapwdlastset = Carbon::createFromTimestamp($sambapwdlastset);
        Carbon::setLocale(config('app.locale'));
        $sambapwdlastset_human = $carbonsambapwdlastset->diffForHumans(Carbon::now());
        $sambapwdlastset_formatted = $carbonsambapwdlastset->format('h:i:sA d-m-Y');;

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

            'fullsearch' => LdapUser::fullSearch($user),

            'sambasid' => $sambasid,
            'sambarid' => $sambarid,
            'sambapwdlastset' => $sambapwdlastset,
            'sambapwdlastset_human' => $sambapwdlastset_human,
            'sambapwdlastset_formatted' => $sambapwdlastset_formatted,

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
//            'jpegphoto' => $user->getJpegPhotoEncoded()
        // TODO optimitzar performance
            'jpegphoto' => ''
//            'attributes' => $user->getAttributes()
        ];
    }

    /**
     * findByUidInLocalUsers
     *
     * @param $localUsers
     * @param $ldapUser
     * @return
     */
    public static function findByUidInLocalUsers($localUsers, $ldapUser)
    {
        return $localUsers->filter(function($user) use ($ldapUser) {
            return $user['uid'] == $ldapUser->uid;
        })->first();
    }

    /**
     * findByEmployeeNumberInLocalUsers.
     *
     * @param $localUsers
     * @param $ldapUser
     * @return
     */
    public static function findByEmployeeNumberInLocalUsers($localUsers, $ldapUser)
    {
        return $localUsers->filter(function($user) use ($ldapUser) {
            return $user['id'] == $ldapUser->employeenumber;
        })->first();
    }

    /**
     * findByEmailInLocalUsers.
     *
     * @param $localUsers
     * @param $ldapUser
     * @return
     */
    public static function findByEmailInLocalUsers($localUsers, $ldapUser)
    {
        return $localUsers->filter(function($user) use ($ldapUser) {
            return $user['email'] == $ldapUser->email;
        })->first();
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
        if (isset($user->employeenumber) && $user->employeenumber ) {
            $user = self::addLocalUser($user, self::findByEmployeeNumberInLocalUsers($localUsers, $user));
            return $user;
        }
        if (isset($user->email) && $user->email ) {
            $user = self::addLocalUser($user, self::findByEmailInLocalUsers($localUsers, $user));
            return $user;
        }
        if (isset($user->uid) && $user->uid ) {
            $user = self::addLocalUser($user, self::findByUidInLocalUsers($localUsers, $user));
            return $user;
        }
        return $user;
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
        if ($user->email !== $scoolUser['email']) {
            $user->errorMessages->push('Email no vàlid. No hi ha cap usuari local amb aquest email personal');
            $user->flags->push(LdapUser::EMAIL_CAN_BE_SYNCED);
            $errors = true;
        }
        if ($user->uid !== $scoolUser['uid']) {
            $user->errorMessages->push('Uid no vàlid. No hi ha cap usuari local amb aquest uid');
            $user->flags->push(LdapUser::UID_CAN_BE_SYNCED);
            $errors = true;
        }
        if (!$errors) $user->inSync = true;
        return $user;
    }

    /**
     * changePassword
     * @param $user
     * @param $password
     * @return \Adldap\Models\Model|array|null
     * @throws ModelNotFoundException
     */
    public static function changePassword($user,$password)
    {
        $user = LdapUser::findByUid($user);
        if (!$user) throw new ModelNotFoundException();
        $user->userPassword = ldap_md5_hash($password);
        $user->sambantpassword = ldap_nt_password($password);
        $user->sambalmpassword = ldap_lm_password($password);
        $user->sambapwdlastset = time();
        $user->save();
        return $user;
    }

    /**
     * Sync.
     *
     * @param $dn
     * @param User $user
     * @return string
     * @throws \Exception
     */
    public static function sync($dn, User $user)
    {
        // TODO

        // GOOGLE

        if (!$user->ldapUser) abort (422, "L'usuari $user->name no té un compte de Ldap associat");

        // UPDATE LDAP --> TODO!!


//        if (!google_user_exists($googleEmail = $user->googleUser->google_email)) abort('422',
//            "No existeix el compte de Google $googleEmail");
//        try {
//            $nameArray = explode(" ", $user->name);
//            $givenName = $nameArray[0];
//            if ($user->person) $givenName =  $user->person->givenName;
//            $familyName = implode(" ",array_splice($nameArray,1,count($nameArray)-1));
//            if ($user->person) $familyName = fullsurname($user->person->sn1,$user->person->sn2);
//            return get_object_vars ((new GoogleDirectory())->editUser([
//                'primaryEmail' => $user->googleUser->google_email,
//                'givenName' => $givenName ,
//                'familyName' => $familyName,
//                'fullName' => $user->name,
//                'hashFunction' => 'SHA-1',
//                'password' => $user->password,
//                'secondaryEmail' => $user->email,
//                'mobile' => $user->mobile,
//                'id' => $user->id
//            ]));
//        } catch (Google_Service_Exception $e) {
//            abort('422',$e);
//        }


        /// MOODLE

//        dd($user);
//        $functionname = 'core_user_update_users';
//        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
//        $client = new Client();
//        $email = $user->corporativeEmail ? $user->corporativeEmail : $user->email;
//        $user = [
//            'id' => $dn,
//            'username' => $user->email,
//            'firstname' => optional($user->person)->givenName,
//            'lastname' => $user->lastname(),
//            'email' => $email,
//            'idnumber' => $user->id
//        ];
//        $params = [
//            'users' => [ $user ]
//        ];
//        $res = $client->request('POST', $serverurl, [
//            'form_params' => $params
//        ]);
//        $result = (string) $res->getBody();
//        if (str_contains($result,'"exception"')) throw new \Exception($result);
//        return $result;
    }

}
