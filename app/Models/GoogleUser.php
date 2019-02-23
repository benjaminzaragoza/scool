<?php

namespace App\Models;

use App\GoogleGSuite\GoogleDirectory;
use Cache;
use Google_Service_Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GoogleUser.
 *
 * @package App\Models
 */
class GoogleUser extends Model
{
    const EMPLOYEE_ID_NUMBER_CAN_BE_SYNCED = 1;
    const PRIMARY_EMAIL_CAN_BE_SYNCED = 2;
    const PERSONAL_EMAIL_CAN_BE_SYNCED = 3;

    protected $guarded = [];

    /**
     * Get the local user associated to Google User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function get($user)
    {
        return (new GoogleDirectory())->user($user);
    }

    /**
     * store.
     *
     * @param $user
     */
    public static function store($user)
    {
        try {
            (new GoogleDirectory())->user($user);
        } catch (Google_Service_Exception $e) {
            dump('Error creating google user. ' . $e->getMessage());
        }
    }

    /**
     * @param $user
     * @return null
     */
    public static function getPersonalEmail($user)
    {
        if ($user->emails) {
            foreach ($user->emails as $email) {
                if (array_key_exists('type',$email) && $email['type'] === 'home') return $email['address'];
            }
        }
        return null;
    }

    /**
     * @param $user
     * @return null
     */
    public static function getMobile($user)
    {
        if ($user->phones) {
            foreach ($user->phones as $phone) {
                if (array_key_exists('type',$phone) && $phone['type'] === 'mobile') return $phone['value'];
            }
        }
        return null;
    }

    /**
     * @param $user
     * @return array
     */
    public static function map($user) {
        return [
            'id' => $user->id,
            'employeeId' => optional($user->externalIds)[0]['value'],
            'mobile' => self::getMobile($user),
            'personalEmail' => self::getPersonalEmail($user),
            'primaryEmail' => $user->primaryEmail,
            'isAdmin' => $user->isAdmin,
            'familyName' => $user->name->familyName,
            'fullName' => $user->name->fullName,
            'givenName' => $user->name->givenName,
            'lastLoginTime' => $user->lastLoginTime,
            'creationTime' => $user->creationTime,
            'suspended' => $user->suspended,
            'suspensionReason' => $user->suspensionReason,
            'thumbnailPhotoUrl' => $user->thumbnailPhotoUrl,
            'orgUnitPath' => $user->orgUnitPath,
            'organizations' => $user->organizations,
            'json' => json_encode($user)
        ];
    }

    /**
     * Search employee id.
     *
     * @param $employeeId
     * @param null $users
     * @return null
     */
    public static function searchEmployeeId($employeeId, $users = null)
    {
        if (!$users) $users = self::getGoogleUsers();
        foreach ($users as $user) {
            if ( $user->employeeId != null && $user->employeeId == $employeeId) return $user;
        }
        return null;
    }

    /**
     * Search personal email.
     *
     * @param $personalEmail
     * @param null $users
     * @return null
     */
    public static function searchPersonalEmail($personalEmail, $users = null)
    {
        if (!$users) $users = self::getGoogleUsers();
        foreach ($users as $user) {
            if ( array_key_exists('personalEmail',$user) && $user->personalEmail != null && $user->personalEmail== $personalEmail) return $user;
        }
        return null;
    }

    public static function searchMobile($mobile, $users = null)
    {
        if (!$users) $users = self::getGoogleUsers();
        foreach ($users as $user) {
            if ( array_key_exists('mobile',$user) && $user->mobile != null && $user->mobile== $mobile) return $user;
        }
        return null;
    }

    public static function searchName($name, $users = null)
    {
        if (!$users) $users = self::getGoogleUsers();
        foreach ($users as $user) {
            if ( array_key_exists('name',$user) && $user['name'] != null && $user['name']== $name) return $user;
        }
        return null;

    }

    /**
     * Search.
     *
     * @param $user
     * @return null
     */
    public static function search($user)
    {
        $users = self::getGoogleUsers();
        if($foundUser = self::searchEmployeeId($user['employeeId'], $users)) return $foundUser;
        if($foundUser = self::searchPersonalEmail($user['personalEmail'], $users)) return $foundUser;
        if($foundUser = self::searchMobile($user['mobile'], $users)) return $foundUser;
        if($foundUser = self::searchName($user['name'], $users)) return $foundUser;
        return null;
    }

    /**
     * @return mixed
     */
    public static function getGoogleUsers()
    {
        $users = collect([]);
        $users = Cache::rememberForever('google_users', function() use ($users){
            $directory = new GoogleDirectory();
            return collect($directory->users());
        });
        return $users->map(function($user) {
            return (object) $user;
        });
    }

    /**
     * initializeUser
     *
     * @param $user
     * @return mixed
     */
    public static function initializeUser($user)
    {
        $user->errorMessages = collect([]);
        $user->inSync = false;
        $user->flags = collect([]);
        return $user;
    }

    public static function adapt($user, $localUsers)
    {
        $user = GoogleUser::initializeUser($user);
        if (isset($user->employeeId) && $user->employeeId ) {
            $user = self::addLocalUser($user, self::findByEmployeeId($localUsers, $user));
            return $user;
        }
        if (isset($user->primaryEmail) && $user->primaryEmail ) {
            $user = self::addLocalUser($user, self::findByPrimaryEmail($localUsers, $user));
            return $user;
        }
        if (isset($user->personalEmail) && $user->personalEmail ) {
            $user = self::addLocalUser($user, self::findByPersonalEmail($localUsers, $user));
            return $user;
        }
        return $user;
    }

    /**
     * findByEmployeeId
     *
     * @param $localUsers
     * @param $googleUser
     * @return
     */
    public static function findByEmployeeId($localUsers, $googleUser)
    {
        return $localUsers->filter(function($user) use ($googleUser) {
            return $user['id'] == $googleUser->employeeId;
        })->first();
    }

    /**
     * findByPrimaryEmail
     *
     * @param $localUsers
     * @param $googleUser
     * @return
     */
    public static function findByPrimaryEmail($localUsers, $googleUser)
    {
        return $localUsers->filter(function($user) use ($googleUser) {
            return $user['corporativeEmail'] == $googleUser->primaryEmail;
        })->first();
    }

    /**
     * findByPersonalEmail
     *
     * @param $localUsers
     * @param $googleUser
     * @return
     */
    public static function findByPersonalEmail($localUsers, $googleUser)
    {
        return $localUsers->filter(function($user) use ($googleUser) {
            return $user['email'] == $googleUser->personalEmail;
        })->first();
    }

    /**
     * addLocalUser
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
        if (intval($user->employeeId) !== intval($scoolUser['id'])) {
            $user->errorMessages->push("EmployeeId no vàlid. No hi ha cap usuari local amb aquest id");
            $user->flags->push(GoogleUser::EMPLOYEE_ID_NUMBER_CAN_BE_SYNCED);
            $errors = true;
        }
        if ($user->primaryEmail !== $scoolUser['corporativeEmail']) {
            $user->errorMessages->push('primaryEmail no vàlid. No hi ha cap usuari local amb aquest email corporatiu');
            $user->flags->push(GoogleUser::PRIMARY_EMAIL_CAN_BE_SYNCED);
            $errors = true;
        }
        if ($user->personalEmail !== $scoolUser['email']) {
            $user->errorMessages->push('personalEmail no vàlid. No hi ha cap usuari local amb aquest email personal');
            $user->flags->push(GoogleUser::PERSONAL_EMAIL_CAN_BE_SYNCED);
            $errors = true;
        }
        if (!$errors) $user->inSync = true;
        return $user;
    }

    /**
     * changePassword.
     *
     * @param $user
     * @param $password
     * @return mixed
     */
    public static function changePassword($user,$password)
    {
        $password = is_sha1($password) ? $password : sha1($password);
        return (new GoogleDirectory())->changePassword($user,$password);
    }

    /**
     * Destroy.
     *
     * @param array|\Illuminate\Support\Collection|int $user
     * @param bool $forget
     * @return int|void
     */
    public static function destroy($user,$forget = true)
    {
        try {
            (new GoogleDirectory())->removeUser($user);
        } catch (Google_Service_Exception $e) {
            abort('422',$e);
        }
        if ($forget) Cache::forget('google_users');
    }
}
