<?php

namespace App\Http\Controllers\Tenant;

use App\Events\GoogleUserNotificationReceived;
use App\Events\GoogleInvalidUserNotificationReceived;
use App\Models\GoogleNotification;
use Illuminate\Http\Request;

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
     * @return array
     */
    public function store(Request $request)
    {
        GoogleNotification::create([
            'channel_id' => GoogleNotification::getChannelId($request),
            'channel_type' => GoogleNotification::getType($request),
            'token' => GoogleNotification::getToken($request),
            'message_number' => GoogleNotification::getMessageNumber($request),
            'expiration_time' => GoogleNotification::getExpiration($request)
        ]);
        if (GoogleNotification::validate($request)) {
            event(new GoogleUserNotificationReceived($request));
            return ['result' => 'Ok'];
        } else {
            event(new GoogleInvalidUserNotificationReceived($request));
            return ['result' => 'Error'];
        }
    }
}
