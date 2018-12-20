<?php

namespace App\Models;

use App\Models\Traits\ApiURI;
use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Department.
 *
 * @package App\Models
 */
class Department extends Model
{
    use FormattedDates, ApiURI;

    protected $guarded = [];

    /**
     * Find by name.
     *
     * @param $name
     * @return mixed
     */
    public static function findByName($name)
    {
        return static::where('name','=',$name)->first();
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
     * Get the department teachers.
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
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

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortname' => $this->shortname,
            'code' => $this->code,
            'order' => (int) $this->order,
            'api_uri' => $this->api_uri,
            'created_at' => $this->created_at,
            'created_at_timestamp' => $this->created_at_timestamp,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'updated_at' => $this->updated_at,
            'updated_at_timestamp' => $this->updated_at_timestamp,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff
        ];
    }
}
