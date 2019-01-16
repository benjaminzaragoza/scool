<?php

namespace App\Models;

use App\Exceptions\UserTypeDoesNotExist;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

/**
 * Class UserType.
 *
 * @package App\Models
 */
class UserType extends Model
{
    const TEACHER = 1;

    protected $guarded = [];

    /**
     * Find by name.
     *
     * @return mixed
     */
    public static function findByName($name)
    {
        $type = static::where('name', $name)->first();

        if (! $type) {
            throw UserTypeDoesNotExist::named($name);
        }

        return $type;
    }

    /**
     * The roles that belong to the user type.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}
