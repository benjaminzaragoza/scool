<?php

namespace App\Http\Requests\People;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PeopleUpdate
 * @package App\Http\Requests
 */
class PeopleUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('people.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'givenName'     => 'required|max:255',
            'sn1'    => 'required|max:255'
        ];
    }
}
