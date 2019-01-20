<?php

namespace App\Http\Controllers\Tenant\Api\Users;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\UserPerson\UserPersonDestroy;
use App\Http\Requests\UserPerson\UserPersonStore;
use App\Http\Requests\UserPerson\UserPersonUpdate;
use App\Models\Person;
use App\Models\User;
use App\Models\UserType;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

/**
 * Class UserPersonController.
 *
 * @package App\Http\Controllers
 */
class UserPersonController extends Controller
{
    /**
     * Store user on database.
     *
     * @param UserPersonStore $request
     * @return mixed
     */
    public function store(UserPersonStore $request)
    {
        $user = User::create([
            'name' => name($request->givenName,$request->sn1,$request->sn2),
            'email' => $request->email,
            'mobile' => $request->mobile,
            'user_type_id' => $request->user_type_id,
            'password' => sha1(str_random())
        ]);
        $person = Person::create([
            'givenName' => format_name($request->givenName),
            'mobile' => $request->mobile,
            'sn1' => format_name($request->sn1),
            'sn2' => format_name($request->sn2),
        ]);
        $person->assignUser($user->id);

        if($request->role) {
            $user->assignRole(Role::findByName($request->role,'web'));
        }

        if ($user->user_type_id) $this->assignRoleToUserByUserType($user->user_type_id, $user);

        return $user->map();
    }

    /**
     * Update.
     *
     * @param UserPersonUpdate $request
     * @param $tenant
     * @param User $user
     * @return array
     */
    public function update(UserPersonUpdate $request, $tenant, User $user )
    {
        if($request->email) $user->email = $request->email;
        if($request->name) $user->name = $request->name;
        if($request->mobile) $user->mobile = $request->mobile;
        $user->save();
        if ($user->person) {
            if ($request->givenName) $user->person->givenName = format_name($request->givenName);
            if ($request->mobile) $user->person->mobile = format_name($request->mobile);
            if ($request->sn1) $user->person->sn1 = format_name($request->sn1);
            if ($request->sn2) $user->person->sn2 = format_name($request->sn2);
            $user->person->save();
        } else {
            $person = Person::create([
                'givenName' => format_name($request->givenName),
                'mobile' => $request->mobile,
                'sn1' => format_name($request->sn1),
                'sn2' => format_name($request->sn2)
            ]);
            $person->assignUser($user->id);
        }
        $user = $user->fresh();
        return $user->map();
    }

    /**
     * Destroy
     *
     * @param UserPersonDestroy $request
     * @param $tenant
     * @param User $user
     * @throws \Exception
     */
    public function destroy(UserPersonDestroy $request, $tenant, User $user )
    {
        $person = Person::where('user_id',$user->id)->first();
        $person->delete();
        $user->delete();
    }

    /**
     * @param $user
     */
    protected function assignRoleToUserByUserType($userType, $user)
    {
        foreach ($this->rolesByUserType($userType) as $role) {
            try {
                $user->assignRole(Role::findByName($role,'web'));
            } catch (RoleDoesNotExist $e) {}
        }
    }

    /**
     * @param $user_type_id
     * @return mixed
     */
    private function rolesByUserType($user_type_id)
    {
        return UserType::ROLES[$user_type_id];
    }

}
