<?php

namespace App\Http\Controllers\Tenant\Api\Curriculum\Studies;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\Curriculum\Studies\StudyTagIndex;
use App\Http\Requests\Curriculum\Studies\StudyTagDestroy;
use App\Http\Requests\Curriculum\Studies\StudyTagShow;
use App\Http\Requests\Curriculum\Studies\StudyTagStore;
use App\Http\Requests\Curriculum\Studies\StudyTagUpdate;
use App\Models\StudyTag;

/**
 * Class StudyTagsController.
 *
 * @package App\Http\Controllers\Tenant\Api\Curriculum\Studies
 */
class StudyTagsController extends Controller
{

    /**
     * Index
     *
     * @param StudyTagIndex $request
     * @return StudyTag[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function index(StudyTagIndex $request)
    {
        return map_collection(StudyTag::all());
    }
    /**
     * Show
     *
     * @param StudyTagShow $request
     * @param $tenant
     * @param StudyTag $tag
     * @return array
     */
    public function show(StudyTagShow $request, $tenant, StudyTag $tag)
    {
        return $tag->map();
    }


    /**
     * Store.
     *
     * @param StudyTagStore $request
     * @return mixed
     */
    public function store(StudyTagStore $request)
    {
        $tag = StudyTag::create($request->only(['value','description','color']));
        return $tag->map();
    }

    /**
     * Update.
     *
     * @param StudyTagUpdate $request
     * @param $tenant
     * @param StudyTag $tag
     * @return StudyTag
     */
    public function update(StudyTagUpdate $request, $tenant, StudyTag $tag)
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
     * @param StudyTagDestroy $request
     * @param $tenant
     * @param StudyTag $tag
     * @return StudyTag
     * @throws \Exception
     */
    public function destroy(StudyTagDestroy $request, $tenant, StudyTag $tag)
    {
        $tag->delete();
        return $tag;
    }
}
