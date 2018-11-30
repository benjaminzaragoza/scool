<?php

namespace App\Http\Controllers\Tenant\Api\Roles;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\People\PeopleUpdate;
use App\Models\Person;
use Spatie\Permission\Models\Role;

/**
 * Class PeopleController
 *
 * @package App\Http\Controllers
 */
class PeopleController extends Controller
{
    public function update(PeopleUpdate $request, $tenant, Person $person)
    {
        Person::update($request->only([
            'givenName',
            'sn1',
            'sn2',
            'identifier_id',
            'birthdate',
            'birthplace_id',
            'gender',
            'civil_status',
            'phone',
            'other_phones',
            'mobile',
            'other_mobiles',
            'email',
            'other_emails',
            'notes',
        ]));
        // TODO ASSIGN USER
//        $table->unsignedInteger('user_id')->nullable();

    }
}
