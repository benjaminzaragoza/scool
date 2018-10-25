<?php

namespace App\Http\Requests\Incidents;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ListIncidents
 * @package App\Http\Requests
 */
class ListIncidents extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('incident.list');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
