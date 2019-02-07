<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Location.
 *
 * @package Acacha\Relationships\Models
 */
class Location extends Model
{
    protected $guarded = [];

    /**
     * Find by name.
     *
     * @param $name
     * @return mixed
     */
    public static function findByName($name)
    {
        return self::where('name',$name)->first();
    }

    /**
     * Province.
     *
     * @param  string  $value
     * @return string
     */
    public function getProvinceAttribute($value)
    {
        if (!$this->postalcode) return null;
        $province = Province::where('postal_code_prefix',substr($this->postalcode, 0, 2))->first();
        if ($province) return $province->name;
        return null;
    }
}
