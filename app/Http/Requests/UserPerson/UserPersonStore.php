<?php

namespace App\Http\Requests\UserPerson;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserPersonStore
 *
 * @package App\Http\Requests
 */
class UserPersonStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('create_users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'givenName' => 'required',
            'sn1'       => 'required',
            'email'     => 'required|email|max:255|unique:users'
        ];
    }
}
