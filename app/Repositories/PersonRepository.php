<?php

namespace App\Repositories;

use App\Models\Person;

/**
 * Class PersonRepository.
 *
 * @package App\Repositories
 */
class PersonRepository
{
    /**
     * Store person.
     *
     * @param $request
     * @return mixed
     */
    public function store($person)
    {
        $personElo = Person::create([
            'identifier_id' => $person->identifier_id,
            'givenName' => $person->givenName ?? null,
            'sn1' => $person->sn1 ?? null,
            'sn2' => $person->sn2 ?? null,
            'birthdate' => $person->birthdate ?? null,
            'birthplace_id' => $person->birthplace_id ?? null,
            'gender' => $person->gender ?? null,
            'civil_status' => $person->civil_status ?? null,
            'phone' => $person->phone ?? null,
            'other_phones' => $person->other_phones ?? null,
            'mobile' => $person->mobile ?? null,
            'other_mobiles' => $person->other_mobiles ?? null,
            'email' => $person->email ?? null,
            'other_emails' => $person->other_emails ?? null,
            'notes' => $person->notes ?? null
        ]);

        if ($person->user_id) $personElo->assignUser($person->user_id);

        return $personElo;
    }
}
