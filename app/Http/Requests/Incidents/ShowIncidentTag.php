<?php

namespace App\Http\Requests\Incidents;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowIncidentTag.
 *
 * @package App\Http\Requests
 */
class ShowIncidentTag extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('incident.show',$this->incident);
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
