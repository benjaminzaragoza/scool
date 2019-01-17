<?php

namespace App\Http\Requests\UserPerson;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserPersonDestroy
 *
 * @package App\Http\Requests
 */
class UserPersonDestroy extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('users.destroy');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
}
