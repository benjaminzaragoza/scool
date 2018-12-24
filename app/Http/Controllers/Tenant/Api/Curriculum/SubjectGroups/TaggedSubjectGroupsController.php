<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups;

use App\Events\SubjectGroups\SubjectGroupTagAdded;
use App\Events\SubjectGroups\SubjectGroupTagRemoved;
use App\Http\Requests\Curriculum\SubjectGroups\TaggedSubjectGroupDestroy;
use App\Http\Requests\Curriculum\SubjectGroups\TaggedSubjectGroupsStore;
use App\Models\SubjectGroupTag;
use App\Http\Controllers\Tenant\Controller;
use App\Models\SubjectGroup;

/**
 * Class TaggedSubjectGroupsController.
 *
 * @package App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups
 */
class TaggedSubjectGroupsController extends Controller
{
    /**
     * Store.
     *
     * @param TaggedSubjectGroupsStore $request
     * @param $tenant
     * @param SubjectGroup $subjectGroup
     * @param SubjectGroupTag $tag
     */
    public function store(TaggedSubjectGroupsStore $request, $tenant, SubjectGroup $subjectGroup, SubjectGroupTag $tag)
    {
        $subjectGroup->addTag($tag);
        event(new SubjectGroupTagAdded($subjectGroup,$tag));
    }

    /**
     * Destroy.
     *
     * @param TaggedSubjectGroupDestroy $request
     * @param $tenant
     * @param SubjectGroup $subjectGroup
     * @param SubjectGroupTag $tag
     * @return void
     */
    public function destroy(TaggedSubjectGroupDestroy $request, $tenant, SubjectGroup $subjectGroup, SubjectGroupTag $tag)
    {
        $subjectGroup->tags()->detach($tag->id);
        event(new SubjectGroupTagRemoved($subjectGroup,$tag));

    }
}
