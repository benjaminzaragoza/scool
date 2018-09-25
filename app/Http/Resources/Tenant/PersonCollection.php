<?php

namespace App\Http\Resources\Tenant;

/**
 * Class PersonCollection.
 *
 * @package App\Http\Resources\Tenant
 */
class PersonCollection
{

    protected $people;

    /**
     * PersonCollection constructor.
     */
    public function __construct($people)
    {
        $this->people = $people;
    }

    /**
     * @return mixed
     */
    public function transform()
    {
        return $this->people->map(function($person) {
            return $person->map();
        });
    }
}