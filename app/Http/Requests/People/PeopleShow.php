<?php

namespace App\Http\Requests\People;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PeopleShow
 * @package App\Http\Requests
 */
class PeopleShow extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('people.show');
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
