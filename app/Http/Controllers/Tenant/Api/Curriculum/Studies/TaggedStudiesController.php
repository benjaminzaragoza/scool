<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Events\Studies\StudyTagAdded;
use App\Events\Studies\StudyTagRemoved;
use App\Models\StudyTag;
use App\Http\Controllers\Tenant\Controller;
use App\Models\Study;

/**
 * Class TaggedStudiesController.
 *
 * @package App\Http\Controllers\Tenant\Api\Curriculum\Studies
 */
class TaggedStudiesController extends Controller
{
    /**
     * Store.
     *
     * @param TaggedStudyStore $request
     * @param $tenant
     * @param Study $study
     * @param StudyTag $tag
     */
    public function store(TaggedStudyStore $request, $tenant, Study $study, StudyTag $tag)
    {
        $study->addTag($tag);
        event(new StudyTagAdded($study,$tag));
    }

    /**
     * Destroy.
     *
     * @param TaggedStudyDestroy $request
     * @param $tenant
     * @param Study $study
     * @param StudyTag $tag
     * @return void
     */
    public function destroy(TaggedStudyDestroy $request, $tenant, Study $study, StudyTag $tag)
    {
        $study->tags()->detach($tag->id);
        event(new StudyTagRemoved($study,$tag));

    }
}
