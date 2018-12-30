<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Subjects;

use App\Events\Subjects\SubjectNameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Subjects\SubjectNameUpdate;
use App\Models\Subject;

/**
 * Class SubjectsNameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class SubjectsNameController extends Controller
{
    /**
     * Update subject name.
     *
     * @param SubjectNameUpdate $request
     * @param $tenant
     * @param Subject $subject
     * @return array
     */
    public function update(SubjectNameUpdate $request, $tenant, Subject $subject)
    {
        $oldSubject = $subject->map(false);
        $subject->name = $request->name;
        $subject->save();
        event(new SubjectNameUpdated($subject, $oldSubject));
        return $subject->map();
    }
}
