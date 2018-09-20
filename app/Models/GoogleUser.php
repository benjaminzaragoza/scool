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

    public static function searchPersonalEmail($personalEmail, $users = null)
    {
        if (!$users) $users = self::getGoogleUsers();
        foreach ($users as $user) {
            if ( $user['personalEmail'] != null && $user['personalEmail']== $personalEmail) return $user;
        }
        return null;
    }

    public static function searchMobile($mobile, $users = null)
    {
        if (!$users) $users = self::getGoogleUsers();
        foreach ($users as $user) {
            if ( $user['mobile'] != null && $user['mobile']== $mobile) return $user;
        }
        return null;

    }

    public static function searchName($name, $users = null)
    {
        if (!$users) $users = self::getGoogleUsers();

    }

    public static function search()
    {

        $users = self::getGoogleUsers();
//        var_dump($users);
//        die();
        if($user = self::searchEmployeeId(130, $users)) {
            dd($user);
            return $user;
        }
        if($user = self::searchPersonalEmail('todo', $users)) return $user;
        if($user = self::searchMobile('todo', $users)) return $user;
        if($user = self::searchName('todo', $users)) return $user;
        return null;
        // - SearchGoogleUser:
        // - Hi ha algun usuari a Google amb employeeId = user.id ?
        // - Hi ha algun usuari a Google amb secondaryEmail = user.email
        // - Hi ha algun usuari a Google amb mobile = user.mobile
        // - Hi ha algun usuari a Google amb givenName i familyName igual al del usuari?
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
