<?php

namespace Tests\Feature\Tenants\Traits;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Role as ScoolRole;

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

    public function loginAsNotificationsManager($guard = 'web')
    {
        initialize_notifications_manager_role();
        return $this->loginAsUsingRole($guard, ScoolRole::NOTIFICATIONS_MANAGER['name']);
    }

    public function loginAsLdapManager($guard = 'web')
    {
        initialize_ldap_manager_role();
        return $this->loginAsUsingRole($guard, ScoolRole::LDAP_MANAGER['name']);
    }

    public function loginAsUsersManager($guard = 'web')
    {
        initialize_users_manager_role();
        return $this->loginAsUsingRole($guard, ScoolRole::USERS_MANAGER['name']);
    }

    public function loginAsPositionsManager($guard = 'web')
    {
        initialize_positions_manager_role();
        return $this->loginAsUsingRole($guard, ScoolRole::POSITIONS_MANAGER['name']);
    }

    public function loginAsPeopleManager($guard = 'web')
    {
        initialize_people_manager_role();
        return $this->loginAsUsingRole($guard, ScoolRole::PEOPLE_MANAGER['name']);
    }

    public function loginAsMoodleManager($guard = 'web')
    {
        initialize_moodle_manager_role();
        return $this->loginAsUsingRole($guard, ScoolRole::MOODLE_MANAGER['name']);
    }

    public function loginAsCurriculumManager($guard = 'web')
    {
        initialize_curriculum_manager_role();
        return $this->loginAsUsingRole($guard, ScoolRole::CURRICULUM_MANAGER['name']);
    }

    public function loginAsIncidentsManager($guard = 'web')
    {
        initialize_incidents_manager_role();
        return $this->loginAsUsingRole($guard, ScoolRole::INCIDENTS_MANAGER['name']);
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
