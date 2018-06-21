<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Teacher.
 *
 * @package App\Http\Resources
 */
class Teacher extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $job = null;
        $job_description = null;
        $job_start_at = null;
        $job_end_at = null;
        $job_family = null;
        $job_specialty = null;
        $job_specialty_code = null;
        $job_order = null;
        if ($jobObject = optional(optional($this->user)->jobs)[0]) {
            $job =  $jobObject->fullCode;
            $job_description =  $jobObject->description;
            $job_start_at =  $jobObject->employee->start_at;
            $job_end_at =  $jobObject->employee->end_at;
            $job_family = optional($jobObject->family)->name;
            $job_specialty = optional($jobObject->specialty)->name;
            $job_specialty_code = optional($jobObject->specialty)->code;
            $job_order = $jobObject->order;
        }
        return [
            'user_id' => optional($this->user)->id,
            'teacher_id' => $this->id,
            'code' => $this->code,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'formatted_created_at' => $this->formatted_created_at,
            'formatted_updated_at' => $this->formatted_updated_at,
            'formatted_updated_at_diff' => $this->formatted_updated_at_diff,
            'hashid' => optional($this->user)->hashid,
            'name' => optional($this->user)->name,
            'email' => optional($this->user)->email,
            'fullname' => optional(optional($this->user)->person)->fullname,
            'department_code' => optional($this->department)->code,
            'department' => optional($this->department)->name,
            'specialty' => optional($this->specialty)->name,
            'specialty_code' => optional($this->specialty)->code,
            'family' => optional(optional($this->specialty)->family)->name,
            'family_code' => optional(optional($this->specialty)->family)->code,
            'force' => optional(optional($this->specialty)->force)->name,
            'administrative_status' => optional($this->administrativeStatus)->name,
            'administrative_status_code' => optional($this->administrativeStatus)->code,
            'job' => $job,
            'job_description' => $job_description,
            'job_start_at' => $job_start_at,
            'job_end_at' => $job_end_at,
            'job_family' => $job_family,
            'job_specialty' => $job_specialty,
            'job_specialty_code' => $job_specialty_code,
            'job_order' => $job_order,
            'full_search' => $this->fullsearch,
            'titulacio_acces' => $this->titulacio_acces,
            'altres_titulacions' => $this->altres_titulacions,
            'idiomes' => $this->idiomes,
            'perfil_professional' => $this->perfil_professional,
            'altres_formacions' => $this->altres_formacions,
            'data_superacio_oposicions' => $this->data_superacio_oposicions,
            'lloc_destinacio_definitiva' => $this->lloc_destinacio_definitiva,
            'data_inici_treball' => $this->data_inici_treball,
            'data_incorporacio_centre' => $this->data_incorporacio_centre,
            'person_notes' => optional(optional($this->user)->person)->notes,
            'givenName' => optional(optional($this->user)->person)->givenName,
            'sn1' => optional(optional($this->user)->person)->sn1,
            'sn2' => optional(optional($this->user)->person)->sn2,
            'birthdate' => optional(optional($this->user)->person)->birthdate,
            'birthplace' => optional(optional(optional($this->user)->person)->birthplace)->name,
            'gender' => optional(optional($this->user)->person)->gender,
            'phone' => optional(optional($this->user)->person)->phone,
            'other_phones' => optional(optional($this->user)->person)->other_phones,
            'mobile' => optional(optional($this->user)->person)->mobile,
            'other_mobiles' => optional(optional($this->user)->person)->other_mobiles,
            'personal_email' => optional(optional($this->user)->person)->email,
            'other_emails'  => optional(optional($this->user)->person)->other_emails,
            'identifier' => optional(optional(optional($this->user)->person)->identifier)->value,
            'identifier_type' => optional(optional(optional(optional($this->user)->person)->identifier)->type)->name,
            'address_name' =>  optional(optional(optional($this->user)->person)->address)->name,
            'address_number' =>  optional(optional(optional($this->user)->person)->address)->number,
            'address_floor' =>  optional(optional(optional($this->user)->person)->address)->floor,
            'address_floor_number' =>  optional(optional(optional($this->user)->person)->address)->floor_number,
            'address_location' =>  optional(optional(optional(optional($this->user)->person)->address)->location)->name,
            'address_postalcode' =>  optional(optional(optional(optional($this->user)->person)->address)->location)->postalcode,
            'address_province' =>  optional(optional(optional(optional($this->user)->person)->address)->province)->name,
            'media' => optional(optional($this->user)->person)->media
        ];
    }
}
