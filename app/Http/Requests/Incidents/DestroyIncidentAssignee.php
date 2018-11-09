<?php

namespace App\Http\Requests\Incidents;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * ClasDestroyreIncidentAssignee.
 *
 * @package App\Http\Requests
 */
class DestroyIncidentAssignee extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('assignee.destroy');
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
