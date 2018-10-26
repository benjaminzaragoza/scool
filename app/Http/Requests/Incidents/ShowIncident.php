<?php

namespace App\Http\Requests\Incidents;

use App\Models\Incident;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowIncident.
 *
 * @package App\Http\Requests
 */
class ShowIncident extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('incident.show', Incident::class);
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
