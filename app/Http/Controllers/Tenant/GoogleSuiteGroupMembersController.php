<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ListGoogleSuiteGroupMembers;
use PulkitJalan\Google\Facades\Google;

/**
 * Class GoogleSuiteGroupMembersController.
 *
 * @package App\Http\Controllers
 */
class GoogleSuiteGroupMembersController extends Controller
{
    /**
     * GoogleSuiteTestConnectionController constructor.
     */
    public function __construct()
    {
        tune_google_client();
    }

    public function index(ListGoogleSuiteGroupMembers $request, $tenant, $group)
    {
        $directory = Google::make('directory');
        try {
//            dd(get_class($directory->groups)); _> Google_Service_Directory_Resource_Groups
            $r = $directory->members->listMembers($group,array('maxResults' => 500));
        } catch (\Exception $e) {
            dump('Error');
            dd($e);
            return $e;
        }
//        dd($r);
        dd(collect($r->members)->pluck('email'));
        dd(collect($r->members)->pluck('primaryEmail'));
//        dd(collect($r->users)->pluck('primaryEmail'));
//        dd(collect($r->groups)->pluck('email'));
//        return $r;
    }


}
