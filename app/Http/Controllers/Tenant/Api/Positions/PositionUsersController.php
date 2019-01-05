<?php

namespace App\Http\Controllers\Tenant\Api\Positions;

use App\Events\Positions\PositionUserDeleted;
use App\Events\Positions\PositionUserStored;
use App\Http\Controllers\Controller;
use App\Http\Requests\Positions\PositionUserDestroy;
use App\Http\Requests\Positions\PositionUserStore;
use App\Models\Position;
use App\Models\User;

/**
 * Class PositionUsersController.
 *
 * @package App\Http\Controllers\Api
 */
class PositionUsersController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param PositionUserStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionUserStore $request, $tenant, Position $position, User $user)
    {
        $user->assignPosition($position);
        event(new PositionUserStored($position, $user));
        return $position->map();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PositionUserDestroy $request
     * @param $tenant
     * @param Position $position
     * @return Position
     * @throws \Exception
     */
    public function destroy(PositionUserDestroy $request, $tenant, Position $position, User $user)
    {
        $user->removePosition($position);
        event(new PositionUserDeleted($position, $user));
        return $position;
    }
}
