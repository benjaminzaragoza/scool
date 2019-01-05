<?php

namespace App\Http\Controllers\Tenant\Api\Positions;

use App\Events\Positions\PositionCodeUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Positions\PositionCodeUpdate;
use App\Models\Position;

/**
 * Class PositionsCodeController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class PositionsCodeController extends Controller
{
    /**
     * Update position code.
     *
     * @param PositionCodeUpdate $request
     * @param $tenant
     * @param Position $position
     * @return array
     */
    public function update(PositionCodeUpdate $request, $tenant, Position $position)
    {
        $oldPosition = $position->map(false);
        $position->code = $request->code;
        $position->save();
        event(new PositionCodeUpdated($position, $oldPosition));
        return $position->map();
    }
}
