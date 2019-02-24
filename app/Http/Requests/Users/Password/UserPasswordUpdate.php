<?php

namespace App\Http\Requests\Users\Password;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserPasswordUpdate
 * @package App\Http\Requests
 */
class UserPasswordUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('users.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:6',
            'options'    => 'sometimes|array'
        ];
    }
}
