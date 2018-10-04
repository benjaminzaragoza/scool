<?php

namespace App\Http\Resources\Tenant;

/**
 * Class IncidentCollecion.
 *
 * @package App\Http\Resources\Tenant
 */
class IncidentCollection
{

    protected $incidents;

    /**
     * IncidentCollecion constructor.
     */
    public function __construct($incidents)
    {
        $this->incidents = $incidents;
    }

    /**
     * @return mixed
     */
    public function transform()
    {
        return $this->incidents->map(function($incident) {
            return $incident->map();
        });
    }
}