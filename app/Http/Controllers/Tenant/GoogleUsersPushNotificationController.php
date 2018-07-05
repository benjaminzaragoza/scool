<?php

namespace App\Http\Controllers\Tenant;

use App\Events\GoogleUserNotificationReceived;
use Illuminate\Http\Request;
use Mail;
use App\Mail\GoogleUserNotificationReceived as GoogleUserNotificationReceivedMail;

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
        dump($request->headers);
        dump(json_encode($request));
        event(new GoogleUserNotificationReceived($request));
        Mail::to('stur@iesebre.com')->send(new GoogleUserNotificationReceivedMail($request));
    }
}
