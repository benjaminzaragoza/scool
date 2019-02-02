<?php

namespace App\Http\Controllers\Tenant\Api\Notifications;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Notifications\NotificationsIndex;
use App\Models\DatabaseNotification;

/**
 * Class NotificationsController.
 *
 * @package App\Http\Controllers\Tenant\Api\Changelog
 */
class NotificationsController extends Controller
{
    /**
     * Index.
     *
     * @param NotificationsIndex $request
     * @return mixed
     */
    public function index(NotificationsIndex $request)
    {
        return map_collection(DatabaseNotification::notifications());
    }
}
