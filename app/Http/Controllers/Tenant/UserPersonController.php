<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\UserPerson\UserPersonDestroy;
use App\Http\Requests\UserPerson\UserPersonStore;
use App\Models\Person;
use App\Models\User;
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
        Person::create([
            'user_id' => $user->id,
            'givenName' => format_name($request->givenName),
            'mobile' => $request->mobile,
            'sn1' => format_name($request->sn1),
            'sn2' => format_name($request->sn2),
        ]);

        if($request->role) {
            $user->assignRole(Role::findByName($request->role,'web'));
        }

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

}
