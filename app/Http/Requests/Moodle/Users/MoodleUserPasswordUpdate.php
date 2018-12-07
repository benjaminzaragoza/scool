<?php

namespace App\Http\Requests\Moodle\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MoodleUserPasswordUpdate.
 *
 * @package App\Http\Requests
 */
class MoodleUserPasswordUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('moodle.user.password.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required'
        ];
    }
}
