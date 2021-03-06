<?php

namespace App\Http\Requests\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ListUsersManagement.
 *
 * @package App\Http\Requests
 */
class ListUsersManagement extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('users.list');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
