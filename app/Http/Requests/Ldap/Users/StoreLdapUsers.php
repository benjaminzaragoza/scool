<?php

namespace App\Http\Requests\Ldap\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreLdapUsers.
 *
 * @package App\Http\Requests
 */
class StoreLdapUsers extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('store-ldap-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //optional
        // path | changePasswordAtNextLogin | hashFunction | password | secondaryEmail | mobile | id
        return [
//            'givenName' => 'required',
//            'familyName' => 'required',
//            'primaryEmail' => 'required|email'
        ];
    }
}
