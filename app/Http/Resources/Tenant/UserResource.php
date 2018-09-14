<?php

namespace App\Http\Resources\Tenant;

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
            $roles = Role::all()->pluck('name')->unique()->toArray();
            $permissions = Permission::all()->pluck('name');
        } else {
            if ($this->roles) $roles = $this->roles->pluck('name')->unique()->toArray();
            if ($this->permissions) $permissions = $this->permissions->pluck('name');
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_updated_at' => $this->formatted_updated_at,
            'roles' => $roles,
            'permissions' => $permissions,
        ];
    }
}
