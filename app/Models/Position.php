<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Position.
 *
 * @package App\Models
 */
class Position extends Model
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
        return static::where('name','=',$name)->first();
    }

    public function map()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
