<?php

namespace App\Http\Requests\UserType;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateUserType
 *
 * @package App\Http\Requests
 */
class UpdateUserType extends FormRequest
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
