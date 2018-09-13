<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class WelcomeEmail
 * @package App\Http\Requests
 */
class WelcomeEmail extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('send-welcome-email');

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
