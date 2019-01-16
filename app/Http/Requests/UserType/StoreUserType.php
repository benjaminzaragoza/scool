<?php

namespace App\Http\Requests\UserType;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreUserType
 *
 * @package App\Http\Requests
 */
class StoreUserType extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|numeric'
        ];
    }
}
