<?php

namespace App\Http\Controllers\Tenant\Web;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Changelog\ListChangelog;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class LoggedUserProfileController.
 *
 * @package App\Http\Controllers\Tenant\Web
 */
class LoggedUserProfileController extends Controller
{
    /**
     * Index.
     *
     * @param ListChangelog $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        return redirect('/users/' . $request->user()->id);
    }
}
