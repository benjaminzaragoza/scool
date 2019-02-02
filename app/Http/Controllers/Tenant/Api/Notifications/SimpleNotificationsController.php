<?php

namespace App\Http\Controllers\Tenant\Api\Notifications;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Notifications\SimpleNotificationsStore;
use App\Http\Requests\Notifications\UserNotificationsIndex;
use App\Models\User;
use App\Notifications\SimpleNotification;

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
        User::findOrFail($request->user)->notify(new SimpleNotification($request->title));
//        $user = $user->fresh();
//        dd ($user->notifications);
//        return $user->fresh()->notifications->last();
    }
}
