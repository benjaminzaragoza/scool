<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Tenant\Teacher as TeacherResource;

/**
 * Class Teacher.
 *
 * @package App\Models
 */
class Teacher extends Model
{
    use FormattedDates;

    protected $guarded = [];

    protected $appends = [
        'full_search',
        'formatted_created_at',
        'formatted_updated_at',
        'formatted_created_at_diff',
        'formatted_updated_at_diff'
    ];

    /**
     * Get Teachers.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function teachers()
    {
        return collect(TeacherResource::collection(
            Teacher::with([
                'specialty',
                'specialty.jobs',
                'specialty.force',
                'specialty.family',
                'administrativeStatus',
                'user',
                'user.googleUser',
                'user.jobs',
                'user.jobs.specialty',
                'user.jobs.family',
                'user.jobs.users',
                'user.jobs.holders',
                'user.person',
                'user.person.birthplace',
                'user.person.identifier',
                'user.person.address',
                'user.person.address.province',
                'user.person.address.location',
                'user.person.media', // TODO
                'department'
            ])->orderByRaw('code + 0')->get()));
    }

    /**
     * Get the user that owns the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that owns the teacher.
     */
    public function administrativeStatus()
    {
        return $this->belongsTo(AdministrativeStatus::class);
    }

    /**
     * Get the specialty that owns the teacher.
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

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
     * full_search accessor.
     *
     * @return string
     */
    public function getFullSearchAttribute()
    {
        $searchString = $this->code;
        if ($this->user) {
            $searchString = $searchString . ' ' .  $this->user->name . ' ' . $this->user->email;
        }
        if ($this->user && $this->user->person) {
            $searchString = $searchString . ' ' .  $this->user->person->givenName . ' ' . $this->user->person->sn1
                . ' ' . $this->user->person->sn2;
        }
        if ($this->specialty) {
            $searchString = $searchString . ' ' .  $this->specialty->code . ' ' .  $this->specialty->name ;
        }
        if ($this->specialty && $this->specialty->family) {
            $searchString = $searchString . ' ' .  $this->specialty->family->code . ' ' .  $this->specialty->family->name ;
        }
        if ($this->administrativeStatus) {
            $searchString = $searchString . ' ' .  $this->administrativeStatus->name . ' ' .  $this->administrativeStatus->code;
        }
        if ($this->specialty && $this->specialty->family && $this->specialty->jobs) {
            $searchString = $searchString . ' ' .  $this->specialty->family->code . '_' . $this->specialty->code;
            if(count($this->specialty->jobs) > 0) {
                $searchString = $searchString . '_' . $this->specialty->jobs[0]->order . '_' . $this->specialty->jobs[0]->code;
            }
        }

        return $searchString;
    }

    /**
     * Get the department that owns the comment.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * First available code.
     *
     * @return string
     */
    public static function firstAvailableCode()
    {
        $codes = self::all()->pluck('code')->toArray();
        foreach (range(1, 999) as $value) {
            $code = sprintf('%03d', $value);
            if (! in_array($code,$codes)) return $code;
        }
    }
}
