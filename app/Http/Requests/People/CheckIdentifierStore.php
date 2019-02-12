<?php

namespace App\Http\Requests\People;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CheckIdentifierStore
 * @package App\Http\Requests
 */
class CheckIdentifierStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('people.index');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identifier_type_id' => 'required',
            'identifier_value' => 'required',
        ];
    }
}
