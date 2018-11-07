<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting.
 *
 * @package App\Models
 */
class Setting extends Model
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'key';
    }

    /**
     * Get setting value by key.
     *
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key)  {
            return optional(self::where('key',$key)->first())->value;
        });
    }

    /**
     * Get setting role by key.
     *
     * @param $key
     * @return mixed
     */
    public static function getRole($key)
    {
        return optional(self::where('key',$key)->first())->role;
    }
}
