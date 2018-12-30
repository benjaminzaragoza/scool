<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Subjects;

use App\Events\Subjects\SubjectCodeUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Subjects\SubjectCodeUpdate;
use App\Models\Subject;

/**
 * Class SubjectsCodeController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class SubjectsCodeController extends Controller
{
    /**
     * Update subject code.
     *
     * @param SubjectCodeUpdate $request
     * @param $tenant
     * @param Subject $subject
     * @return array
     */
    public function update(SubjectCodeUpdate $request, $tenant, Subject $subject)
    {
        $oldSubject = $subject->map(false);
        $subject->code = $request->code;
        $subject->save();
        event(new SubjectCodeUpdated($subject, $oldSubject));
        return $subject->map();
    }
}
