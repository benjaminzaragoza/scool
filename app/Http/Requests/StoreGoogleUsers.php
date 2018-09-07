<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreGoogleUsers.
 *
 * @package App\Http\Requests
 */
class StoreGoogleUsers extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('store-gsuite-users');
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
            'givenName' => 'required',
            'familyName' => 'required',
            'primaryEmail' => 'required|email'
        ];
    }
}
