<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupTagIndex;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupTagDestroy;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupTagShow;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupTagStore;
use App\Http\Requests\Curriculum\SubjectGroups\SubjectGroupTagUpdate;
use App\Models\SubjectGroupTag;

/**
 * Class SubjectGrousTagsController.
 *
 * @package App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups
 */
class SubjectGroupsTagsController extends Controller
{

    /**
     * Index
     *
     * @param SubjectGroupTagIndex $request
     * @return SubjectGroupTag[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function index(SubjectGroupTagIndex $request)
    {
        return map_collection(SubjectGroupTag::all());
    }
    /**
     * Show
     *
     * @param SubjectGroupTagShow $request
     * @param $tenant
     * @param SubjectGroupTag $tag
     * @return array
     */
    public function show(SubjectGroupTagShow $request, $tenant, SubjectGroupTag $tag)
    {
        return $tag->map();
    }


    /**
     * Store.
     *
     * @param SubjectGroupTagStore $request
     * @return mixed
     */
    public function store(SubjectGroupTagStore $request)
    {
        $tag = SubjectGroupTag::create($request->only(['value','description','color']));
        return $tag->map();
    }

    /**
     * Update.
     *
     * @param SubjectGroupTagUpdate $request
     * @param $tenant
     * @param SubjectGroupTag $tag
     * @return SubjectGroupTag
     */
    public function update(SubjectGroupTagUpdate $request, $tenant, SubjectGroupTag $tag)
    {
        $tag->description = $request->description;
        $tag->value = $request->value;
        $tag->color = $request->color;
        $tag->save();
        return $tag;
    }

    /**
     * Destroy.
     *
     * @param SubjectGroupTagDestroy $request
     * @param $tenant
     * @param SubjectGroupTag $tag
     * @return SubjectGroupTag
     * @throws \Exception
     */
    public function destroy(SubjectGroupTagDestroy $request, $tenant, SubjectGroupTag $tag)
    {
        $tag->delete();
        return $tag;
    }
}
