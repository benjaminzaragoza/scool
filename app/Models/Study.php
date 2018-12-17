<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Study.
 *
 * @package App
 */
class Study extends Model
{
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
            'name' => $this->name,
            'shortname' => $this->shortname,
            'code' => $this->code,
        ];
    }

}
