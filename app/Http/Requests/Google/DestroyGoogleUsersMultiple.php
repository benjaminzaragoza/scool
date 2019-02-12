<?php

namespace App\Http\Requests\Google;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DestroyGoogleUsersMultiple.
 *
 * @package App\Http\Requests
 */
class DestroyGoogleUsersMultiple extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('delete-gsuite-users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'users' => 'required'
        ];
    }
}
