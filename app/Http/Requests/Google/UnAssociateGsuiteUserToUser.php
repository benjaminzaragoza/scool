<?php

namespace App\Http\Requests\Google;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UnAssociateGsuiteUserToUser
 *
 * @package App\Http\Requests
 */
class UnAssociateGsuiteUserToUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('users.store');
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
