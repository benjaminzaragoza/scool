<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Google\ListGoogleUsers;
use App\Models\GoogleUser;

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
//        dump($request->all());
        $user = GoogleUser::search([
            'employeeId' => $request->employeeId,
            'personalEmail' => $request->personalEmail,
            'mobile' => $request->mobile,
            'name' => $request->name,
        ]);
        if (!$user) abort(404,'Google user not found');
        return $user;
    }
}
