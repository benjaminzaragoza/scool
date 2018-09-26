<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use App\Revisionable\Revisionable;
use Carbon\Carbon;
use App\Revisionable\RevisionableTrait;

/**
 * Class Job.
 *
 * @package App\Tenant
 */
class Job extends Revisionable
{
    use FormattedDates;

    /**
     * Identifiable name.
     *
     * @return string
     */
    public function identifiableName()
    {
        return 'Plaça: ' . $this->fullcode;
    }

    /**
     * Locale name.
     *
     * @return string
     */
    public function localeName()
    {
        return 'la plaça';
    }

    /**
     * Locale identifier.
     *
     * @return mixed
     */
    public function localeIdentifier()
    {
        return $this->fullcode;
    }

    protected $revisionCreationsEnabled = true;

    protected $guarded = [];

    protected $appends = [
        'formatted_created_at',
        'formatted_updated_at',
        'formatted_created_at_diff',
        'formatted_updated_at_diff',
    ];

    /**
     * Get the job type
     */
    public function type()
    {
        return $this->belongsTo(JobType::class);
    }

    /**
     * Get the job family
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the job specialty
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Get the current active user associated to the job.
     */
    public function getActiveUserAttribute($value) {
        $numUsers= count($this->users);
        if ( $numUsers == 0) return null;
        if ( $numUsers == 1) return $this->holders->first();
        if ( $numUsers > 1) {
            $actives = $this->users->filter(function ($user) {
                if ($user->employee) {
                    if ($user->employee->start_at && $user->employee->end_at) {
                        return Carbon::now()->between(
                            Carbon::parse($user->employee->start_at),
                            Carbon::parse($user->employee->end_at));
                    }
                    if ($user->employee->start_at && $user->employee->end_at == null ) {
                        return Carbon::now()->gt(Carbon::parse($user->employee->start_at));
                    }
                }
            });
            if($actives->first()) return $actives->first();
        }
        return $this->holders->first();
    }

    /**
     * Get the user associated to the job.
     */
    public function users()
    {
        return $this->belongsToMany(User::class,'employees')->as('employee')->withPivot('start_at', 'end_at')->withTimestamps();
    }

    /**
     * Get the holder of the place/job.
     */
    public function holders()
    {
        return $this->belongsToMany(User::class,'employees')->wherePivot('holder', 1);
    }

    /**
     * Get the holder of the place/job.
     */
    public function substitutes()
    {
        return $this->belongsToMany(User::class,'employees')->wherePivot('holder', 0)->withPivot('start_at', 'end_at');
    }

    /**
     * Add substitute.
     *
     * @param $user
     * @return $this
     */
    public function addSubtitute($user)
    {
        Employee::create([
            'user_id' => $user->id,
            'job_id' => $this->id,
            'holder' => 0,
            'start_at' => Carbon::now()
        ]);
        return $this;
    }

    /**
     * Get the description.
     *
     * @param  string  $value
     * @return string
     */
    public function getDescriptionAttribute($value)
    {
        $familyName = '';
        if ($this->family) $familyName = ' de la família ' . $this->family->name;
        $specialtyName = '';
        if ($this->specialty) $specialtyName = $this->specialty->name;
        return 'Plaça num ' . $this->order . $familyName . ', especialitat ' . $specialtyName;
    }

    /**
     * Get the full code.
     *
     * @param  string  $value
     * @return string
     */
    public function getFullcodeAttribute($value)
    {
        $familyCode = 'TOTES';
        if ($this->family) $familyCode = $this->family->code;
        $specialtyCode = '';
        if ($this->specialty) $specialtyCode = $this->specialty->code;
        return $familyCode . '_' . $specialtyCode . '_' . $this->order . '_' . $this->code;
    }

    /**
     * Find by code.
     *
     * @param $code
     * @return mixed
     */
    public static function findByCode($code)
    {
        return self::where('code',$code)->first();
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

    /**
     * Get holder
     */
    public function getHolderAttribute()
    {
        $holder = null;
        if ($this->holders->first()) {
            return $this->holders->first();
        }
        return $holder;
    }

    /**
     * holder_description attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getHolderDescriptionAttribute($value)
    {
        $holder_name = optional($this->holder)->name;
        if ($teacher = optional(optional($this->holder)->teacher)) {
            $holder_description = $teacher->code . ' ' . $holder_name;
        } else {
            $holder_description = $holder_name;
        }
        return trim($holder_description);
    }

    /**
     * full_search attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getFullSearchAttribute($value)
    {
        return trim("$this->fullcode $this->holder_description");
    }
}
