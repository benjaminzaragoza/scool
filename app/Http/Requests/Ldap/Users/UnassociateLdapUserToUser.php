<?php

namespace App\Http\Requests\Ldap\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UnassociateLdapUserToUser
 *
 * @package App\Http\Requests
 */
class UnassociateLdapUserToUser extends FormRequest
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
        return [];
    }
}
