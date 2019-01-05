<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\PositionNameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Studies\PositionNameUpdate;
use App\Models\Incident;
use App\Models\Study;

/**
 * Class StudiesNameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class PositionsNameController extends Controller
{
    /**
     * Update study name.
     *
     * @param PositionNameUpdate $request
     * @param $tenant
     * @param Study $study
     * @return array
     */
    public function update(PositionNameUpdate $request, $tenant, Study $study)
    {
        $oldStudy = $study->map(false);
        $study->name = $request->name;
        $study->save();
        event(new PositionNameUpdated($study, $oldStudy));
        return $study->map();
    }
}
