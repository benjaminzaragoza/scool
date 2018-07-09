<?php

namespace App\Http\Controllers\Tenant;

use App\Events\GoogleUserNotificationReceived;
use Illuminate\Http\Request;
use Mail;

/**
 * Class GoogleUsersPushNotificationController.
 *
 * @package App\Http\Controllers\Tenant
 */
class GoogleUsersPushNotificationController extends Controller
{
    /**
     * Store.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        event(new GoogleUserNotificationReceived($request));
    }
}
