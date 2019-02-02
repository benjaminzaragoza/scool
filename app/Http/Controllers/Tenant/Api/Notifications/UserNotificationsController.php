<?php

namespace App\Http\Controllers\Tenant\Api\Notifications;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Notifications\UserNotificationsIndex;

/**
 * Class UserNotificationsController.
 *
 * @package App\Http\Controllers\Tenant\Api\Changelog
 */
class UserNotificationsController extends Controller
{
    /**
     * Index.
     *
     * @param UserNotificationsIndex $request
     * @return mixed
     */
    public function index(UserNotificationsIndex $request)
    {
        return $request->user()->notifications;
    }
}
