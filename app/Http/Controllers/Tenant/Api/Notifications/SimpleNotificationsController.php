<?php

namespace App\Http\Controllers\Tenant\Api\Notifications;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Notifications\SimpleNotificationsStore;
use App\Http\Requests\Notifications\UserNotificationsIndex;
use App\Models\User;

/**
 * Class SimpleNotificationsController.
 *
 * @package App\Http\Controllers\Tenant\Api\Changelog
 */
class SimpleNotificationsController extends Controller
{
    /**
     * Store.
     *
     * @param UserNotificationsIndex $request
     * @return mixed
     */
    public function store(SimpleNotificationsStore $request)
    {
        ($user = User::findOrFail($request->user))->notify(new SimpleNotification($request->title));
        return $user->fresh()->notifications->last();
    }
}
