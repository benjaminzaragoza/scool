<?php

namespace App\Http\Resources\Tenant;

/**
 * Class UserCollection.
 *
 * @package App\Http\Resources\Tenant
 */
class UserCollection
{

    protected $users;

    /**
     * UserCollection constructor.
     */
    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function transform()
    {
        return $this->users->map(function($user) {
            return $user->map();
        });
    }
}