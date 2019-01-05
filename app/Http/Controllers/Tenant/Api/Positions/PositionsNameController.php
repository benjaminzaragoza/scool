<?php

namespace App\Http\Controllers\Tenant\Api\Positions;

use App\Events\Positions\PositionNameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Positions\PositionNameUpdate;
use App\Models\Position;

/**
 * Class PositionsNameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class PositionsNameController extends Controller
{
    /**
     * Update position name.
     *
     * @param PositionNameUpdate $request
     * @param $tenant
     * @param Position $position
     * @return array
     */
    public function update(PositionNameUpdate $request, $tenant, Position $position)
    {
        $oldPosition = $position->map(false);
        $position->name = $request->name;
        $position->save();
        event(new PositionNameUpdated($position, $oldPosition));
        return $position->map();
    }
}
