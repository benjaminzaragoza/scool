<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subject.
 *
 * @package App\Models
 */
class Subject extends Model
{
    use FormattedDates, ApiURI;

    protected $guarded = [];

    /**
     * Find by code.
     *
     * @param $code
     * @return mixed
     */
    public static function findByCode($code)
    {
        return self::where('code', $code)->first();
    }

    /**
     * Get the post that owns the comment.
     */
    public function subject_group()
    {
        return $this->belongsTo(SubjectGroup::class);
    }

    /**
     * Get the subject's study.
     */
    public function study()
    {
        return $this->belongsTo(Study::class);
    }

    /**
     * Get the subject's course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortname' => $this->shortname,
            'code' => $this->code,
            'number' => (int) $this->number,
            'hours' => (int) $this->hours,
            'start' => $this->start,
            'end' => $this->end,

            'api_uri' => $this->api_uri,

            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,

            'subject_group_id' => (int) $this->subject_group_id,
            'subject_group_name' => optional($this->subject_group)->name,
            'subject_group_shortname' => optional($this->subject_group)->shortname,
            'subject_group_code' => optional($this->subject_group)->code,
            'subject_group_number' => (int) optional($this->subject_group)->number,
            'subject_group_hours' => (int) optional($this->subject_group)->hours,
            'subject_group_free_hours' => (int) optional($this->subject_group)->free_hours,
            'subject_group_week_hours' => (int) optional($this->subject_group)->week_hours,
            'subject_group_start' => optional($this->subject_group)->start,
            'subject_group_end' => optional($this->subject_group)->end,
            'subject_group_type' => optional($this->subject_group)->type,

            'study_id' => (int) $this->study_id,
            'study_name' => optional($this->study)->name,
            'study_shortname' => optional($this->study)->shortname,
            'study_code' => optional($this->study)->code,

            'course_id' => (int) $this->course_id,
            'course_name' => optional($this->course)->name,
            'course_code' => optional($this->course)->code,
            'course_order' => (int) optional($this->course)->order
        ];
    }

}
