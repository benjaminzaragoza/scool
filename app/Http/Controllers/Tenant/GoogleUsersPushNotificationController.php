<?php

namespace App\Http\Controllers\Tenant;

use App\Events\GoogleUserNotificationReceived;
use App\Events\InvalidGoogleUserNotificationReceived;
use App\Models\GoogleNotification;
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
        if (GoogleNotification::validate($request)) {
            event(new GoogleUserNotificationReceived($request));
        } else {
            event(new InvalidGoogleUserNotificationReceived($request));
        }

    }
}
