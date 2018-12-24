<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SubjectGroup.
 *
 * @package App
 */
class SubjectGroup extends Model
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
     * Get the subject's study.
     */
    public function study()
    {
        return $this->belongsTo(Study::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function week_lessons()
    {
        return $this->hasMany(WeekLesson::class)->orderBy('day')->orderBy('start');
    }

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortname' => $this->shortname,
            'description' => $this->description,
            'code' => $this->code,
            'number' => (int) $this->number,
            'hours' => (int) $this->hours,
            'free_hours' => (int) $this->hours,
            'week_hours' => (int) $this->hours,
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

            'study_id' => (int) $this->study_id,
            'study_name' => optional($this->study)->name,
            'study_shortname' => optional($this->study)->shortname,
            'study_code' => optional($this->study)->code,
            'full_search' => $this->full_search,

            'tags' => map_collection($this->tags)
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(SubjectGroupTag::class,'tagged_subject_groups')->withTimestamps();
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
}
