<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NotificationsIndex;
use App\Http\Controllers\Tenant\Controller;

/**
 * Class NotificationController.
 *
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    /**
     * NotificationsIndex.
     *
     * @param NotificationsIndex $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NotificationsIndex $request)
    {
//        dump('shit');
        $notifications = $request->user()->notifications;
//        dd($notifications);
        return view('tenants.notifications.index', compact('notifications'));
    }
}
