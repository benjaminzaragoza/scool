<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowGoogleSuiteGroup.
 *
 * @package App\Http\Requests
 */
class ShowGoogleSuiteGroup extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (! Auth::user()) return false;
        return Auth::user()->can('list-gsuite-groups');
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
