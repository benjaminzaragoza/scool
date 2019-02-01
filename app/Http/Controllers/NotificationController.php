<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NotificationsIndex;
use Illuminate\Http\Request;
use App\Events\NotificationRead;
use App\Events\NotificationReadAll;
use App\Notifications\HelloNotification;
use NotificationChannels\WebPush\PushSubscription;

/**
 * Class NotificationController.
 *
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('last', 'dismiss');
    }

    /**
     * NotificationsIndex.
     *
     * @param NotificationsIndex $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NotificationsIndex $request)
    {
        $notifications = $request->user()->notifications;
        return view('tenants.notifications.index', compact('notifications'));
    }


    /**
     * Create a new notification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->user()->notify(new HelloNotification);

        return response()->json('Notification sent.', 201);
    }


}
