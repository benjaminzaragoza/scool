<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\NotificationsIndex;
use App\Http\Controllers\Tenant\Controller;
use App\Models\DatabaseNotification;
use App\Models\User;

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
        $notifications = map_collection(DatabaseNotification::notifications());
        $userNotifications = $request->user()->notifications;
        $users = map_collection(User::all());
        return view('tenants.notifications.index', compact('userNotifications','notifications','users'));
    }
}
