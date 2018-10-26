<?php

namespace App\Http\Requests\Incidents;

use App\Models\Incident;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DeleteIncident.
 *
 * @package App\Http\Requests
 */
class DeleteIncident extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('incident.destroy',Incident::class);
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
