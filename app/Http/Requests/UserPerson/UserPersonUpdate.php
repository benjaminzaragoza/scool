<?php

namespace App\Http\Requests\UserPerson;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserPersonUpdate
 *
 * @package App\Http\Requests
 */
class UserPersonUpdate extends FormRequest
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
            'email' => 'sometimes|required|email|max:255|unique:users',
        ];
    }
}
