<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IdentifierType.
 *
 * @package Acacha\Relationships\Models
 *
 */
class IdentifierType extends Model
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
     * map
     * @return array
     */
    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
