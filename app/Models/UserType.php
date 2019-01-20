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
    const STUDENT = 2;
    const JANITOR = 3;
    const ADMINISTRATIVE = 4;
    const FAMILY = 5;

    const ROLES = [
        UserType::TEACHER => [

        ],
        UserType::STUDENT => [

        ],
        UserType::JANITOR => [

        ],
        UserType::ADMINISTRATIVE => [

        ],
        UserType::FAMILY => [

        ],
    ];

    const TYPES = [
        UserType::TEACHER => 'teacher',
        UserType::STUDENT => 'student',
        UserType::JANITOR => 'janitor',
        UserType::ADMINISTRATIVE => 'administrative',
        UserType::FAMILY => 'family'
    ];

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
