<?php

namespace App\Http\Requests\Moodle\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UnassociateMoodleUserToUser
 *
 * @package App\Http\Requests
 */
class UnassociateMoodleUserToUser extends FormRequest
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
