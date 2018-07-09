<?php

namespace App\Http\Controllers\Tenant;

use App\GoogleGSuite\GoogleDirectory;
use App\Http\Requests\WatchGoogleSuiteUsers;

/**
 * Class GoogleUsersWatchController.
 *
 * @package App\Http\Controllers
 */
class GoogleUsersWatchController extends Controller
{
    /**
     * GoogleUsersWatchController constructor.
     */
    public function __construct()
    {
        tune_google_client();
    }

    /**
     * Store.
     *
     * @param WatchGoogleSuiteUsers $request
     * @return \Exception
     * @throws \Exception
     */
    public function store(WatchGoogleSuiteUsers $request)
    {
        $directory = new GoogleDirectory();
        $directory->watch();
    }
}
