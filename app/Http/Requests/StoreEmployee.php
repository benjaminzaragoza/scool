<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreEmployee.
 *
 * @package App\Http\Requests
 */
class StoreEmployee extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('store-job');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'holder' => 'sometimes|boolean',
            'start_at' => 'sometimes|date',
            'end_at' => 'sometimes|date'
        ];
    }
}
