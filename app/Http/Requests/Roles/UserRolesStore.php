<?php

namespace App\Http\Requests\Roles;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserRolesStore
 * @package App\Http\Requests\Roles
 */
class UserRolesStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('user.role.store', $this->role);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'roles' => 'required'
        ];
    }
}
