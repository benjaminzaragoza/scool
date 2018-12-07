<?php

namespace App\Http\Requests\Moodle\Users;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MoodleUserCheckStore.
 *
 * @package App\Http\Requests
 */
class MoodleUserCheckStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('moodle.user.show');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => 'required'
        ];
    }
}
