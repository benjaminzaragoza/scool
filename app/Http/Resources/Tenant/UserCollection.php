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
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'mobile' => $user->mobile,
                'last_login' => $user->last_login,
                'last_login_ip' => $user->last_login_ip,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'roles' => $user->roles->pluck('name')->unique()->toArray(),
                'formatted_created_at' => $user->formatted_created_at,
                'formatted_updated_at' => $user->formatted_updated_at,
                'admin' => $user->admin
            ];
        });
    }
}