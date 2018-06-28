<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ListGoogleSuiteGroup;
use PulkitJalan\Google\Facades\Google;

/**
 * Class GoogleSuiteGroupsController.
 *
 * @package App\Http\Controllers
 */
class GoogleSuiteGroupsController extends Controller
{
    /**
     * GoogleSuiteTestConnectionController constructor.
     */
    public function __construct()
    {
        tune_google_client();
    }

    public function index(ListGoogleSuiteGroup $request)
    {
        $directory = Google::make('directory');
        try {
//            dd(get_class($directory->groups)); _> Google_Service_Directory_Resource_Groups
            $r = $directory->groups->listGroups(array('domain' => 'iesebre.com', 'maxResults' => 500));
        } catch (\Exception $e) {
            dump('Error');
            dd($e);
            return $e;
        }
//        dd($r);
//        dd(collect($r->users)->pluck('primaryEmail'));
        dd(collect($r->groups)->pluck('email'));
        return $r;
    }

    /**
     * @param ListGoogleSuiteGroup $request
     * @return \Exception
     */
    public function show(ListGoogleSuiteGroup $request, $tenant, $group)
    {
        $directory = Google::make('directory');
        try {
//            dd(get_class($directory->groups)); _> Google_Service_Directory_Resource_Groups
            $r = $directory->groups->get($group);
//            public function get($groupKey, $optParams = array())
        } catch (\Exception $e) {
            dump('Error');
            dd($e);
            return $e;
        }
        dd($r);
//        dd(collect($r->users)->pluck('primaryEmail'));
        dd(collect($r->groups)->pluck('email'));
        return $r;
    }

}
