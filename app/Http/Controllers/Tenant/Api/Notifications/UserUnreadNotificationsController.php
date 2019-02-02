<?php

namespace App\Http\Controllers\Tenant\Api\Notifications;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Notifications\UserNotificationsDestroy;
use App\Http\Requests\Notifications\UserNotificationsIndex;
use App\Models\DatabaseNotification;

/**
 * Class UserUnreadNotificationsController.
 *
 * @package App\Http\Controllers\Tenant\Api\Changelog
 */
class UserUnreadNotificationsController extends Controller
{
    /**
     * Index.
     *
     * @param UserNotificationsIndex $request
     * @return mixed
     */
    public function index(UserNotificationsIndex $request)
    {
        return $request->user()->unreadNotifications;
    }

    /**
     * destroy.
     *
     * @param UserNotificationsDestroy $request
     * @return mixed
     */
    public function destroy(UserNotificationsDestroy $request, $tenant, DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return $request->user()->unreadNotifications;
    }

    /**
     * destroyAll.
     *
     * @param UserNotificationsDestroy $request
     * @return mixed
     */
    public function destroyAll(UserNotificationsDestroy $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return $request->user()->fresh()->unreadNotifications;
    }
}
