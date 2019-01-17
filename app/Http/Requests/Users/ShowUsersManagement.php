<?php

namespace App\Http\Requests\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowUsersManagement.
 *
 * @package App\Http\Requests
 */
class ShowUsersManagement extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('users.show');
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
