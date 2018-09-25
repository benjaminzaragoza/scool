<?php

namespace App\Models;

use App\Revisionable\Revisionable;

/**
 * Class Specialty.
 *
 * @package App\Models
 */
class Specialty extends Revisionable
{
    protected $guarded = [];

    protected $appends = [
        'full_search'
    ];

    /**
     * Identifiable name.
     *
     * @return string
     */
    public function identifiableName()
    {
        return $this->code;
    }

    /**
     * Find by code.
     *
     * @param $code
     * @return mixed
     */
    public static function findByCode($code)
    {
        return static::where('code','=',$code)->first();

    }

    /**
     * Get the jobs associated to the family.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the force.
     */
    public function force()
    {
        return $this->belongsTo(Force::class);
    }

    /**
     * Get the family.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * full_search attribute.
     *
     * @param  string $value
     * @return string
     */
    public function getFullSearchAttribute($value)
    {
        return "$this->code $this->name";
    }
}
