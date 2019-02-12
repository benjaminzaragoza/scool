<?php

namespace App\Http\Requests\Google;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class WatchGoogleSuiteUsers.
 *
 * @package App\Http\Requests
 */
class WatchGoogleSuiteUsers extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        Auth::shouldUse('api');
        return Auth::user()->can('watch-gsuite-users');
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
