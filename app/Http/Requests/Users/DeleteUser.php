<?php

namespace App\Http\Requests\Users;

use Auth;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DeleteUser
 * @package App\Http\Requests
 */
class DeleteUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('users.destroy');
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
