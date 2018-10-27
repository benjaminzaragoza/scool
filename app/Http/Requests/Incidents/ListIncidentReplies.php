<?php

namespace App\Http\Requests\Incidents;

use App\Models\Incident;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ListIncidentReplies
 * @package App\Http\Requests\Incidents
 */
class ListIncidentReplies extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('incident.list', Incident::class);
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
