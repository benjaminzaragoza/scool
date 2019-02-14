<?php

namespace App\Models;

use App\GoogleGSuite\GoogleDirectory;
use Cache;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

/**
 * Class MoodleUser.
 *
 * @package App\Models
 */
class MoodleUser extends Model
{
    const ID_NUMBER_CAN_BE_SYNCED = 1;
    const USERNAME_CAN_BE_SYNCED = 2;

    protected $guarded = [];

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * findByIdNumber
     *
     * @param $localUsers
     * @param $moodleUser
     * @return
     */
    public static function findByIdNumber($localUsers, $moodleUser)
    {
        return $localUsers->filter(function($user) use ($moodleUser) {
            return $user['id'] == $moodleUser->idnumber;
        })->first();
    }

    /**
     * findByIdEmail
     *
     * @param $localUsers
     * @param $moodleUser
     * @return
     */
    public static function findByIdEmail($localUsers, $moodleUser)
    {
        return $localUsers->filter(function($user) use ($moodleUser) {
            return $user['corporativeEmail'] == $moodleUser->username;
        })->first();
    }

    public static function adapt($user, $localUsers)
    {
        $user = MoodleUser::initializeUser($user);
        if (isset($user->idnumber) && $user->idnumber ) {
            $user = self::addLocalUser($user, self::findByIdNumber($localUsers, $user));
        } else {
            $user = self::addLocalUserByUsername($localUsers, $user);
        }
        return $user;
    }

    public static function initializeUser($user)
    {
        $user->errorMessages = collect([]);
        $user->inSync = false;
        $user->flags = collect([]);
        return $user;
    }

    /**
     * addLocalUserByUsername
     *
     * @param $user
     * @return mixed
     */
    public static function addLocalUserByUsername($localUsers, $user)
    {
        if ($user->username) {
            $scoolUser = self::findByIdEmail($localUsers , $user);
            if ($scoolUser) {
                $user->localUser = $scoolUser->mapSimple();
                $user = self::userInSync($user, $scoolUser);
            }
        }
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
        if ($user->username !== $scoolUser['corporativeEmail']) {
            $user->errorMessages->push("Nom d'usuari Moodle incorrecte. S'ha trobat un usuari local amb Idnumber de Moodle però l'usuari de Moodle no correspon amb l'email corporatiu");
            $user->flags->push(MoodleUser::USERNAME_CAN_BE_SYNCED);
            $errors = true;
        }
        if (intval($user->idnumber) !== intval($scoolUser['id'])) {
            $user->errorMessages->push('Idnumber no vàlid. No hi ha cap usuari local amb aquest id');
            $user->flags->push(MoodleUser::ID_NUMBER_CAN_BE_SYNCED);
            $errors = true;
        }
        if (!$errors) $user->inSync = true;
        return $user;
    }

    /**
     * @param $user
     * @return array
     */
    public static function map($user) {
        return [
//            'id' => $user->id,
//            'employeeId' => optional($user->externalIds)[0]['value'],
//            'mobile' => self::getMobile($user),
//            'personalEmail' => self::getPersonalEmail($user),
//            'primaryEmail' => $user->primaryEmail,
//            'isAdmin' => $user->isAdmin,
//            'familyName' => $user->name->familyName,
//            'fullName' => $user->name->fullName,
//            'givenName' => $user->name->givenName,
//            'lastLoginTime' => $user->lastLoginTime,
//            'creationTime' => $user->creationTime,
//            'suspended' => $user->suspended,
//            'suspensionReason' => $user->suspensionReason,
//            'thumbnailPhotoUrl' => $user->thumbnailPhotoUrl,
//            'orgUnitPath' => $user->orgUnitPath,
//            'organizations' => $user->organizations,
//            'json' => json_encode($user)
        ];
    }

    /**
     * All.
     *
     * @param array $criteria
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function all($criteria = [['key' => 'email', 'value' => '%']])
    {
        $functionname = 'core_user_get_users';
        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new Client();
        $params = [ 'criteria' => $criteria ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        $result = json_decode($res->getBody());
        return $result->users;
    }

    /**
     * Store.
     *
     * @param $user
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function store($user) {
        $functionname = 'core_user_create_users';
        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new Client();
        $params = [
            'users' => [ $user ]
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        $result = (string) $res->getBody();
        if (str_contains($result,'"exception"')) throw new \Exception($result);
        $users = json_decode(explode('\n',$result)[1]);
        return $users[0];
    }

    /**
     * getByIdNumber.
     *
     * @param $user
     * @return null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getByIdNumber($idNumber) {
        $results = self::all([['key' => 'idnumber', 'value' => $idNumber]]);
        return empty($results) ? null : $results;
    }

    /**
     * Get.
     *
     * @param $user
     * @return null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function get($user) {
        // NOTA: BE CAREFUL! username could be and email so this cannot work as you expected!
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            $results = self::all([['key' => 'email', 'value' => $user]]);
            return empty($results) ? null : $results[0];
        }
        if (intval($user) != 0) {
            if (is_integer(intval($user))) {
                $results = self::all([['key' => 'id', 'value' => $user]]);
                return empty($results) ? null : $results[0];
            }
        }
        $results = self::all([['key' => 'username', 'value' => $user]]);
        return empty($results) ? null : $results[0];
    }

    /**
     * Destroy.
     *
     * @param $user
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function destroy($user) {
        $user = is_integer(intval($user)) ? $user : self::get($user)->id;
        $functionname = 'core_user_delete_users';
        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new Client();
        $params = [
            'userids' => [ $user ]
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        $result = (string) $res->getBody();
        if (str_contains($result,'"exception"')) throw new \Exception($result);
    }

    /**
     * Change password.
     *
     * @param $userId
     * @param $password
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function change_password($userId, $password)
    {
        $functionname = 'core_user_update_users';
        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new Client();
        $user = [
            'id' => $userId,
            'password' => $password
        ];
        $params = [
            'users' => [ $user ]
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        $result = (string) $res->getBody();
        if (str_contains($result,'"exception"')) throw new \Exception($result);
        return $result;
    }

    /**
     * Change password.
     *
     * @param $userId
     * @param $idnumber
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function change_idnumber($userId, $idnumber)
    {
        $functionname = 'core_user_update_users';
        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new Client();
        $user = [
            'id' => $userId,
            'idnumber' => $idnumber
        ];
        $params = [
            'users' => [ $user ]
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        $result = (string) $res->getBody();
        if (str_contains($result,'"exception"')) throw new \Exception($result);
        return $result;
    }

    /**
     * Sync.
     *
     * @param $userId
     * @param User $user
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function sync($userId, User $user)
    {
        $functionname = 'core_user_update_users';
        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new Client();
        $email = $user->corporativeEmail ? $user->corporativeEmail : $user->email;
        $user = [
            'id' => $userId,
            'username' => $user->email,
            'firstname' => optional($user->person)->givenName,
            'lastname' => $user->lastname(),
            'email' => $email,
            'idnumber' => $user->id
        ];
        $params = [
            'users' => [ $user ]
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        $result = (string) $res->getBody();
        if (str_contains($result,'"exception"')) throw new \Exception($result);
        return $result;
    }
}
