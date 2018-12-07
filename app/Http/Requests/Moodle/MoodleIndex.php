<?php

namespace App\Http\Requests\Moodle;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MoodleIndex.
 *
 * @package App\Http\Requests
 */
class MoodleIndex extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('moodle.index');
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
