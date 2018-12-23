<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Study.
 *
 * @package App
 */
class Study extends Model
{
    use FormattedDates, ApiURI;

    protected $guarded = [];

    /**
     * Assign family.
     *
     * @param $family
     */
    public function assignFamily($family)
    {
        $family = is_object($family) ? $family : Family::where('code',$family)->firstorFail();
        $family->addStudy($this);
    }

    public function assignDepartment($department)
    {
        $department = is_object($department) ? $department : Department::where('code',$department)->firstorFail();
        $department->addStudy($this);
    }

    /**
     * Add tag.
     *
     * @param $tag
     */
    public function addTag($tag)
    {
        $this->tags()->save($tag);
    }

    public function assignTag($tag)
    {
        $tag = is_object($tag) ? $tag : StudyTag::where('value',$tag)->firstorFail();
        $this->tags()->save($tag);
    }

    public function assignSubjectGroup($subjectGroup)
    {
        $subjectGroup = is_object($subjectGroup) ? $subjectGroup : SubjectGroup::where('code',$subjectGroup)->firstorFail();
        $this->subjectGroups()->save($subjectGroup);
    }

    /**
     * Get the family record associated with the study.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the department record associated with the study.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(StudyTag::class,'tagged_studies')->withTimestamps();
    }

    /**
     * Get the subjectGroups for the study
     */
    public function subjectGroups()
    {
        return $this->hasMany(SubjectGroup::class)->orderBy('number');
    }

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortname' => $this->shortname,
            'code' => $this->code,
            'subjects_number' => $this->subjects_number,
            'subject_groups_number' => $this->subject_groups_number,
            'api_uri' => $this->api_uri,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'department_id' => optional($this->department)->id,
            'department_name' => optional($this->department)->name,
            'department_shortname' => optional($this->department)->shortname,
            'department_code' => optional($this->department)->code,
            'family_id' => optional($this->family)->id,
            'family_name' => optional($this->family)->name,
            'family_code' => optional($this->family)->code,
            'tags' => map_collection($this->tags),
            'full_search' => $this->full_search,

            'subjectGroups' => map_collection($this->subjectGroups)
        ];
    }

    /**
     * full_search accessor.
     *
     * @return string
     */
    public function getFullSearchAttribute()
    {
        return "$this->name $this->shortname $this->code";
    }

}
