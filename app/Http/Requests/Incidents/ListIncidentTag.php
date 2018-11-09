<?php

namespace App\Http\Requests\Incidents;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ListIncidentTag.
 *
 * @package App\Http\Requests
 */
class ListIncidentTag extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('incident.list',$this->incident);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
