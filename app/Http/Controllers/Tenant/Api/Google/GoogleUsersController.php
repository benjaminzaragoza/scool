<?php

namespace App\Http\Controllers\Tenant\Api\Google;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Google\DestroyGoogleUsers;
use App\Http\Requests\Google\DestroyGoogleUsersMultiple;
use App\Http\Requests\Google\ListGoogleUsers;
use App\Http\Requests\Google\StoreGoogleUsers;
use App\Models\GoogleUser;
use Cache;
use Google_Service_Exception;

/**
 * Class GoogleUsersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class GoogleUsersController extends Controller
{
    /**
     * Index.
     *
     * @param ListGoogleUsers $request
     * @return mixed
     */
    public function index(ListGoogleUsers $request)
    {
        if (!$request->cache) Cache::forget('google_users');
        return GoogleUser::getGoogleUsers();
    }

    /**
     * Store.
     *
     * @param StoreGoogleUsers $request
     * @return array
     */
    public function store(StoreGoogleUsers $request)
    {
        if (google_user_exists($request->primaryEmail)) abort('422','Google user already exists');
        $directory = new GoogleDirectory();
        $user = [
            'givenName' => $request->givenName,
            'familyName' => $request->familyName,
            'primaryEmail' => $request->primaryEmail
        ];
        if ($request->path) $user['path'] = $request->path;
        if ($request->changePasswordAtNextLogin) $user['changePasswordAtNextLogin'] = $request->changePasswordAtNextLogin;
        if ($request->hashFunction) $user['hashFunction'] = $request->hashFunction;
        if ($request->password) $user['password'] = $request->password;
        if ($request->secondaryEmail) $user['secondaryEmail'] = $request->secondaryEmail;
        if ($request->mobile) $user['mobile'] = $request->mobile;
        if ($request->id) $user['id'] = $request->id;

        try {
            $user = get_object_vars($directory->user($user));
            Cache::forget('google_users');
            return $user;
        } catch (Google_Service_Exception $e) {
            abort('422',$e);
        }
    }

    /**
     * @param DestroyGoogleUsers $request
     * @param $tenant
     * @param $user
     */
    public function destroy(DestroyGoogleUsers $request, $tenant, $user)
    {
        try {
            (new GoogleDirectory())->removeUser($user);
        } catch (Google_Service_Exception $e) {
            abort('422',$e);
        }
        Cache::forget('google_users');
    }

    /**
     * @param DestroyGoogleUsersMultiple $request
     */
    public function destroyMultiple(DestroyGoogleUsersMultiple $request)
    {
        try {
            foreach ($request->users as $user) {
                (new GoogleDirectory())->removeUser($user);
            }
        } catch (Google_Service_Exception $e) {
            abort('422',$e);
        }
        Cache::forget('google_users');
    }
}
