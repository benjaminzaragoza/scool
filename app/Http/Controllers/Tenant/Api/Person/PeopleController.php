<?php

namespace App\Http\Controllers\Tenant\Api\Person;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\People\PeopleIndex;
use App\Http\Requests\People\PeopleShow;
use App\Http\Requests\People\PeopleStore;
use App\Http\Requests\People\PeopleUpdate;
use App\Models\Person;

/**
 * Class PeopleController
 *
 * @package App\Http\Controllers
 */
class PeopleController extends Controller
{

    /**
     * Index.
     * @param PeopleIndex $request
     * @return mixed
     */
    public function index(PeopleIndex $request)
    {
        return Person::getPeople();
    }

    /**
     * show.
     * @param PeopleIndex $request
     * @return mixed
     */
    public function show(PeopleShow $request, $tenant, Person $person)
    {
        return $person->map();
    }

    /**
     * Store.
     *
     * @param PeopleStore $request
     * @return mixed
     */
    public function store(PeopleStore $request)
    {
        $data = $request->only([
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
        ]);
        if ($data['givenName']) $data['givenName'] =ucfirst($data['givenName']);
        if ($data['sn1']) $data['sn1'] =ucfirst($data['sn1']);
        if ($data['sn2']) $data['sn2'] =ucfirst($data['sn2']);
        $person = Person::create($data);
        if ($request->user_id) {
            $person->user_id = $request->user_id;
            $person->save();
        }
        return $person;
    }

    /**
     * Update.
     *
     * @param PeopleUpdate $request
     * @param $tenant
     * @param Person $person
     * @return Person
     */
    public function update(PeopleUpdate $request, $tenant, Person $person)
    {
        $person->update($request->only([
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
        if ($request->user_id) {
            $person->user_id = $request->user_id;
            $person->save();
        }
        return $person;
    }
}
