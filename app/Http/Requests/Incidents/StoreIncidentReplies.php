<?php

namespace App\Http\Requests\Incidents;

use App\Models\Incident;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreIncidentReplies
 * @package App\Http\Requests\Incidents
 */
class StoreIncidentReplies extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('incident.store', Incident::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required'
        ];
    }
}
