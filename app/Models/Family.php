<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use App\Revisionable\Revisionable;

/**
 * Class Family.
 *
 * @package App\Models
 */
class Family extends Revisionable
{
    use FormattedDates, ApiURI;

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

    public function addStudy($study)
    {
        $this->studies()->save($study);
    }

    /**
     * Get the studies associated to family.
     */
    public function studies()
    {
        return $this->hasMany(Study::class);
    }

    /**
     * Map.
     *
     * @return array
     */
    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'api_uri' => $this->api_uri,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'studies' => map_collection($this->studies)
        ];
    }
}
