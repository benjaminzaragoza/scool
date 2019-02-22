<?php

namespace App\Http\Requests\Ldap;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateLdapUserPassword.
 *
 * @package App\Http\Requests
 */
class UpdateLdapUserPassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('ldap.users.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:6'
        ];
    }
}
