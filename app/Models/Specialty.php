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
}
