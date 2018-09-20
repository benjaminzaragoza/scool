<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\AddUser;
use App\Http\Requests\AddUserPerson;
use App\Http\Requests\DestroyUserPerson;
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
     * @param AddUser $request
     * @return mixed
     */
    public function store(AddUserPerson $request)
    {
        $user = User::create([
            'name' => name($request->givenName,$request->sn1,$request->sn2),
            'email' => $request->email,
            'mobile' => $request->mobile,
            'user_type_id' => $request->user_type_id,
            'password' => sha1(str_random())
        ]);
        $person = Person::create([
            'user_id' => $user->id,
            'givenName' => format_name($request->givenName),
            'mobile' => $request->mobile,
            'sn1' => format_name($request->sn1),
            'sn2' => format_name($request->sn2),
        ]);

        if($request->role) {
            $user->assignRole(Role::findByName($request->role,'web'));
        }

        return collect([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'user_type_id' => $user->user_type_id,
            'givenName' => $person->givenName,
            'sn1' => $person->sn1,
            'sn2' => $person->sn2
        ]);
    }

    /**
     * Destroy
     *
     * @param DestroyUserPerson $request
     * @param $tenant
     * @param User $user
     * @throws \Exception
     */
    public function destroy(DestroyUserPerson $request, $tenant, User $user )
    {
        $person = Person::where('user_id',$user->id)->first();
        $person->delete();
        $user->delete();
    }

}
