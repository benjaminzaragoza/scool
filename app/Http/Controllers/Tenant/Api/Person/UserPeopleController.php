<?php

namespace App\Http\Controllers\Tenant\Api\Person;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\People\PeopleIndex;
use App\Http\Requests\People\PeopleShow;
use App\Http\Requests\People\PeopleStore;
use App\Http\Requests\People\PeopleUpdate;
use App\Http\Requests\People\UserPeopleStore;
use App\Models\Address;
use App\Models\Identifier;
use App\Models\Person;
use App\Models\User;

/**
 * Class UserPeopleController
 *
 * @package App\Http\Controllers
 */
class UserPeopleController extends Controller
{
    /**
     * Store.
     *
     * @param UserPeopleStore $request
     * @param $tenant
     * @param User $user
     * @return mixed
     */
    public function store(UserPeopleStore $request, $tenant, User $user)
    {
        $person = null;
        $personData = $this->formatPersonData($request, $user);
        if ($user->person) {
            $person = $user->person;
            $user->person->update($personData);
        }
        else {
            $person = Person::create($personData);
            $person->user_id = $user->id;
            $person->save();
        }
        if ($request->identifier) $this->setIdentifier($person, $request->identifier);
        if ($request->other_identifiers) $this->setOtherIdentifiers($person, $request->other_identifiers);
        if ($request->address) $this->setAddress($person, $request->address);
        return collect($person->map());
    }

    /**
     * formatPersonData
     *
     * @param $request
     * @param $user
     * @return array
     */
    protected function formatPersonData($request, $user)
    {
        $personData = [];
        if ($request->givenName) $personData['givenName'] = $request->givenName;
        if ($request->sn1) $personData['sn1'] = $request->sn1;
        if ($request->sn2) $personData['sn2'] = $request->sn2;
        if ($request->other_emails) $personData['other_emails'] = json_encode(explode(',',$request->other_emails));
        if ($request->birthdate) $personData['birthdate'] = $request->birthdate;
        if ($request->birthplace_id) $personData['birthplace_id'] = $request->birthplace_id;
        if ($request->birthplace) $personData['birthlocation'] = $this->formatLocation($request->birthplace);
        if ($request->gender) $personData['gender'] = $request->gender;
        if ($request->civil_status) $personData['civil_status'] = $request->civil_status;
        if ($request->phone) $personData['phone'] = $request->phone;
        if ($request->other_phones) $personData['other_phones'] = json_encode(explode(',',$request->other_phones));
        if ($request->other_mobiles) $personData['other_mobiles'] = json_encode(explode(',',$request->other_mobiles));
        if ($request->email) $personData['email'] = $request->email;
        if ($request->other_emails) $personData['other_emails'] = json_encode(explode(',',$request->other_emails));
        if ($request->notes) $personData['notes'] = $request->notes;
        if (count($personData) === 0) abort('422',"No s'ha proporcionat cap dada personal!");
        if ($request->email) $personData['email'] = $request->email;
        else $personData['email'] = $user->email;
        if ($request->mobile) $personData['mobile'] = $request->mobile;
        elseif ($user->mobile) $personData['mobile'] = $user->mobile;
        return $personData;
    }

    /**
     * formatLocation.
     *
     * @param $location
     * @return string
     */
    protected function formatLocation($location)
    {
        $formattedLocation = '';
        if ($location['location']) $formattedLocation = $location['location'];
        if ($location['postalcode']) $formattedLocation = $formattedLocation . ' ' . $location['postalcode'];
        if ($location['province']) $formattedLocation = $formattedLocation . ' ' . $location['province'];
        return $formattedLocation;
    }

    /**
     * setIdentifier.
     *
     * @param $user
     * @param $identifier
     */
    protected function setIdentifier(Person $person, $identifier)
    {
        $identifier = Identifier::create([
            'type_id' => $identifier['type_id'],
            'value' => $identifier['value']
        ]);

        $person->identifier_id = $identifier->id;
        $person->save();
    }

    /**
     * setIdentifier.
     *
     * @param $user
     * @param $identifier
     */
    protected function setOtherIdentifiers(Person $person, $other_identifiers)
    {
        foreach ($other_identifiers as $identifier) {
            $person->identifiers()->save(
                Identifier::create([
                    'type_id' => $identifier['type_id'],
                    'value' => $identifier['value']
                ])
            );
        }
    }

    /**
     * setAddress.
     *
     * @param $user
     * @param $identifier
     */
    protected function setAddress(Person $person, $address)
    {
        $addressData = [];
        if ($address['name']) $addressData['name'] = $address['name'];
        if ($address['number']) $addressData['number'] = $address['number'];
        if ($address['floor']) $addressData['floor'] = $address['floor'];
        if ($address['floor_number']) $addressData['floor_number'] = $address['floor_number'];
        if ($address['location_id']) $addressData['location_id'] = $address['location_id'];
        if ($address['province_id']) $addressData['province_id'] = $address['province_id'];
        $address = Address::create($addressData);
        $person->assignAddress($address);
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
