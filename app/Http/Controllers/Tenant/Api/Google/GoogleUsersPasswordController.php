<?php

namespace App\Http\Controllers\Tenant\Api\Google;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Google\DestroyGoogleUsers;
use App\Http\Requests\Google\DestroyGoogleUsersMultiple;
use App\Http\Requests\Google\GoogleUsersPasswordUpdate;
use App\Http\Requests\Google\ListGoogleUsers;
use App\Http\Requests\Google\StoreGoogleUsers;
use App\Models\GoogleUser;
use Cache;
use Google_Service_Exception;

/**
 * Class GoogleUsersPasswordController.
 *
 * @package App\Http\Controllers\Tenant
 */
class GoogleUsersPasswordController extends Controller
{
    /**
     * Update.
     *
     * @param GoogleUsersPasswordUpdate $request
     * @param $tenant
     * @param $user
     */
    public function update(GoogleUsersPasswordUpdate $request, $tenant, $user)
    {
        GoogleUser::changePassword($user,$request->password);
   }
}
