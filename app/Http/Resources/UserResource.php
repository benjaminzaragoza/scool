<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Class UserResource.
 *
 * @package App\Http\Resources
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $roles = [];
        $permissions = [];
        if ($this->resource->isSuperAdmin()) {
            $roles = Role::all()->pluck('name');
            $permissions = Permission::all()->pluck('name');
        } else {
            if ($this->roles) $roles = $this->roles->pluck('name');
            if ($this->permissions) $permissions = $this->permissions->pluck('name');
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'formatted_created_at_date' => $this->formatted_created_at_date,
            'roles' => $roles,
            'permissions' => $permissions,
        ];
    }
}
