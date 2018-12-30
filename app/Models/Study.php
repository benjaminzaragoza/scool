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

    /**
     * @param $department
     */
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

    /**
     * @param $tag
     */
    public function assignTag($tag)
    {
        $tag = is_object($tag) ? $tag : StudyTag::where('value',$tag)->firstorFail();
        $this->tags()->save($tag);
    }

    /**
     * @param $subjectGroup
     */
    public function assignSubjectGroup($subjectGroup)
    {
        $subjectGroup = is_object($subjectGroup) ? $subjectGroup : SubjectGroup::where('code',$subjectGroup)->firstorFail();
        $this->subjectGroups()->save($subjectGroup);
    }

    /**
     * @param $subject
     */
    public function assignSubject($subject)
    {
        $subject = is_object($subject) ? $subject : Subject::where('code',$subject)->firstorFail();
        $this->subjects()->save($subject);
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

    /**
     * Get the subjects for the study
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class)->orderBy('number');
    }

    /**
     * @return array
     */
    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'shortname' => $this->shortname,
            'code' => $this->code,
            'subjects_number' => $this->subjects_number,
            'subject_groups_number' => $this->subject_groups_number,
            'completed' => $this->completed,
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

            'subjectGroups' => map_simple_collection($this->subjectGroups),
            'subjects' => map_simple_collection($this->subjects)
        ];
    }

    /**
     * @return array
     */
    public function mapSimple()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
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
            'full_search' => $this->full_search,
            'completed' => $this->completed
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

    /**
     * slug accessor.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        return str_slug($this->name);
    }

    /**
     * completed accessor.
     *
     * @return string
     */
    public function getCompletedAttribute()
    {
        if ($this->subjectGroups && $this->subjects) {
            return ((int) $this->subjects_number === count($this->subjects)) &&
                   ((int) $this->subject_groups_number === count($this->subjectGroups));
        }
        return false;
    }

}
