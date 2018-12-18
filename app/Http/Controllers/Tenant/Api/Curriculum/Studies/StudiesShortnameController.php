<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\StudyShortnameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Studies\UpdateStudyShortname;
use App\Models\Incident;
use App\Models\Study;

/**
 * Class StudiesShortnameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class StudiesShortnameController extends Controller
{
    /**
     * Update study name.
     *
     * @param UpdateStudyShortname $request
     * @param $tenant
     * @param Incident $incident
     * @return Incident
     */
    public function update(UpdateStudyShortname $request, $tenant,Study $study)
    {
        $oldStudy = $study->map(false);
        $study->shortname = $request->shortname;
        $study->save();
        event(new StudyShortnameUpdated($study, $oldStudy));
        return $study->map();
    }
}
