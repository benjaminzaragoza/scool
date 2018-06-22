<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\AddUser;
use App\Http\Requests\AddUserPerson;
use App\Models\Person;
use App\Models\User;

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
            'password' => sha1(str_random())
        ]);
        $person = Person::create([
            'user_id' => $user->id,
            'givenName' => $request->givenName,
            'sn1' => $request->sn1,
            'sn2' => $request->sn2,
        ]);

        return collect([
            'name' => $user->name,
            'email' => $user->email,
            'givenName' => $person->givenName,
            'sn1' => $person->sn1,
            'sn2' => $person->sn2
        ]);
    }

}
