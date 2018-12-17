<?php

namespace Tests\Feature\Tenants\Traits;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Trait CanLogin
 * @package Tests\Feature\Traits
 */
trait CanLogin
{
    /**
     * @param null $guard
     * @return mixed
     */
    protected function login($guard = null)
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,$guard);
        return $user;
    }

    /**
     * @param null $guard
     * @return mixed
     */
    protected function loginAsUsingRole($guard,$role)
    {
        $user = factory(User::class)->create();

        $roles = is_array($role) ? $role : [$role];

        foreach ($roles as $role) {
            $user->assignRole($role);
        }
        $this->actingAs($user,$guard);
        return $user;
    }

    public function loginAsUsersManager($guard = 'web')
    {
        initialize_users_manager_role();
        return $this->loginAsUsingRole($guard, 'UsersManager');
    }

    public function loginAsPeopleManager($guard = 'web')
    {
        initialize_people_manager_role();
        return $this->loginAsUsingRole($guard, 'PeopleManager');
    }

    public function loginAsMoodleManager($guard = 'web')
    {
        initialize_moodle_manager_role();
        return $this->loginAsUsingRole($guard, 'MoodleManager');
    }

    public function loginAsCurriculumManager($guard = 'web')
    {
        initialize_curriculum_manager_role();
        return $this->loginAsUsingRole($guard, 'CurriculumManager');
    }

    /**
     * @param null $guard
     * @return mixed
     */
    protected function loginWithPermission($guard,$permission)
    {
        $user = factory(User::class)->create();
        Permission::create([
            'name' => $permission
        ]);
        $user->givePermissionTo($permission);
        $this->actingAs($user,$guard);
        return $user;
    }

    protected function loginAsSuperAdmin($guard = null)
    {
        $user = factory(User::class)->create();
        $user->admin = true;
        $user->save();
        $this->actingAs($user,$guard);
        return $user;
    }
}
