<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\PositionShortnameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Studies\PositionShortnameUpdate;
use App\Models\Incident;
use App\Models\Study;

/**
 * Class StudiesShortnameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class PositionsShortnameController extends Controller
{
    /**
     * Update study name.
     *
     * @param PositionShortnameUpdate $request
     * @param $tenant
     * @param Incident $incident
     * @return Incident
     */
    public function update(PositionShortnameUpdate $request, $tenant, Study $study)
    {
        $oldStudy = $study->map(false);
        $study->shortname = $request->shortname;
        $study->save();
        event(new PositionShortnameUpdated($study, $oldStudy));
        return $study->map();
    }
}
