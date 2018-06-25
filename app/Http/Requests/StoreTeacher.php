<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreTeacher.
 *
 * @package App\Http\Requests
 */
class StoreTeacher extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('store-teachers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|numeric',
            'code' => 'required',
            'specialty_id' => 'required|numeric',
            'department_id' => 'required|numeric',
            'administrative_status_id' => 'required|numeric'
        ];
    }
}
