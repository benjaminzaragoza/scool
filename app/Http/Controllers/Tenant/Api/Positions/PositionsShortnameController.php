<?php

namespace App\Http\Controllers\Tenant\Api\Positions;

use App\Events\Positions\PositionShortnameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Positions\PositionShortnameUpdate;
use App\Models\Incident;
use App\Models\Position;

/**
 * Class PositionsShortnameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class PositionsShortnameController extends Controller
{
    /**
     * Update position name.
     *
     * @param PositionShortnameUpdate $request
     * @param $tenant
     * @param Incident $incident
     * @return Incident
     */
    public function update(PositionShortnameUpdate $request, $tenant, Position $position)
    {
        $oldPosition = $position->map(false);
        $position->shortname = $request->shortname;
        $position->save();
        event(new PositionShortnameUpdated($position, $oldPosition));
        return $position->map();
    }
}
