<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Subjects;

use App\Events\Subjects\SubjectShortnameUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Curriculum\Subjects\SubjectShortnameUpdate;
use App\Models\Subject;

/**
 * Class SubjectsShortnameController.
 *
 * @package App\Http\Controllers\Tenant\Api
 */
class SubjectsShortnameController extends Controller
{
    /**
     * Update subject shortname.
     *
     * @param SubjectShortnameUpdate $request
     * @param $tenant
     * @param Subject $subject
     * @return array
     */
    public function update(SubjectShortnameUpdate $request, $tenant, Subject $subject)
    {
        $oldSubject = $subject->map(false);
        $subject->shortname = $request->shortname;
        $subject->save();
        event(new SubjectShortnameUpdated($subject, $oldSubject));
        return $subject->map();
    }
}
