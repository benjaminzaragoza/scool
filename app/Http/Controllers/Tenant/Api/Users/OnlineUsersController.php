<?php

namespace App\Http\Controllers\Tenant\Api\Users;

use App\Http\Controllers\Tenant\Controller;
use App\Models\User;

/**
 * Class OnlineUsersController.
 *
 * @package App\Http\Controllers\Tenant\Api\Users
 */
class OnlineUsersController extends Controller
{
    /**
     * Index
     * @param \Request $request
     * @return
     */
    public function index(\Request $request)
    {
        $onlineUsers = User::all()->filter(function ($user) {
            return $user['online'];
        });
        return map_simple_collection($onlineUsers->values());
    }

}
