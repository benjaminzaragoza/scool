<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\PositionCodeUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Studies\PositionCodeUpdate;
use App\Models\Study;

/**
 * Class StudiesCodeController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class StudiesCodeController extends Controller
{
    /**
     * Update study code.
     *
     * @param PositionCodeUpdate $request
     * @param $tenant
     * @param Study $study
     * @return array
     */
    public function update(PositionCodeUpdate $request, $tenant, Study $study)
    {
        $oldStudy = $study->map(false);
        $study->code = $request->code;
        $study->save();
        event(new PositionCodeUpdated($study, $oldStudy));
        return $study->map();
    }
}
