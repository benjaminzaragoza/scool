<?php

namespace App\Models;

use App\GoogleGSuite\GoogleDirectory;
use Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * Class GoogleUser.
 *
 * @package App\Models
 */
class GoogleUser extends Model
{
    protected $guarded = [];

    /**
     * Get the user that owns the phone.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
            if ( $user['employeeId'] != null && $user['employeeId']== $employeeId) return $user;
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
            if ( array_key_exists('personalEmail',$user) && $user['personalEmail'] != null && $user['personalEmail']== $personalEmail) return $user;
        }
        return null;
    }

    public static function searchMobile($mobile, $users = null)
    {
        if (!$users) $users = self::getGoogleUsers();
        foreach ($users as $user) {
            if ( array_key_exists('mobile',$user) && $user['mobile'] != null && $user['mobile']== $mobile) return $user;
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
        return Cache::rememberForever('google_users', function() use ($users){
            $directory = new GoogleDirectory();
            return collect($directory->users());
        });
    }
}
