<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Study.
 *
 * @package App
 */
class Study extends Model
{
    use FormattedDates;

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

    public function assignTag($tag)
    {
        $tag = is_object($tag) ? $tag : StudyTag::where('value',$tag)->firstorFail();
        $this->tags()->save($tag);
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

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortname' => $this->shortname,
            'code' => $this->code,
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
            'family_code' => optional($this->family)->code
        ];
    }

}
