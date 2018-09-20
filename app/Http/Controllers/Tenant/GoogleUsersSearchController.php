<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\ListGoogleUsers;
use App\Models\GoogleUser;
use Google_Service_Exception;

/**
 * Class GoogleUsersSearchController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class GoogleUsersSearchController extends Controller
{
    /**
     * @return mixed
     */
    protected function search(ListGoogleUsers $request)
    {
        $user = GoogleUser::search([
            'employeeId' => $request->employeeId,
            'employeeId' => $request->employeeId,
            'employeeId' => $request->employeeId,
            'employeeId' => $request->employeeId,
        ]);
        dd($user);
        if (!$user) abort(404,'Google user not found');
        return $user;
    }
}
