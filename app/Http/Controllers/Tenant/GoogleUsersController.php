<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\DestroyGoogleUsers;
use App\Http\Requests\ListGoogleUsers;
use App\Http\Requests\StoreGoogleUsers;
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
     * Show.
     *
     * @param ListGoogleUsers $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ListGoogleUsers $request)
    {
        $users = $this->getGoogleUsers();
        return view('tenants.google_users.show', compact('users'));
    }

    /**
     * @return mixed
     */
    protected function getGoogleUsers()
    {
        $users = collect([]);
        return Cache::rememberForever('google_users', function() use ($users){
            $directory = new GoogleDirectory();
            return collect($directory->users());
        });
    }

    /**
     * Index.
     *
     * @param ListGoogleUsers $request
     * @return mixed
     */
    public function index(ListGoogleUsers $request)
    {
        Cache::forget('google_users');
        return $this->getGoogleUsers();
    }

    /**
     * Store.
     *
     * @param StoreGoogleUsers $request
     * @return array
     */
    public function store(StoreGoogleUsers $request)
    {
        if (google_user_exists($request->primaryEmail)) abort('422','Already exists');
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
            $users = get_object_vars($directory->user($user));
            Cache::forget('google_users');
            return $users;
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
            Cache::forget('google_users');
        } catch (Google_Service_Exception $e) {
            abort('422',$e);
        }
    }
}
