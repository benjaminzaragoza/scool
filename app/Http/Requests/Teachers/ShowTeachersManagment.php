<?php

namespace App\Http\Requests\Teachers;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowTeachersManagment.
 *
 * @package App\Http\Requests
 */
class ShowTeachersManagment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('show-teachers');
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
