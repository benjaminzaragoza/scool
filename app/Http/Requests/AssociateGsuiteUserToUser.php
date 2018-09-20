<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AssociateGsuiteUserToUser
 *
 * @package App\Http\Requests
 */
class AssociateGsuiteUserToUser
    extends FormRequest
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
            'google_id'     => 'required|unique:google_users',
            'google_email'    => 'required|email|max:255|unique:google_users'
        ];
    }
}
