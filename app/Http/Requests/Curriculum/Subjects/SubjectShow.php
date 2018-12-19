<?php

namespace App\Http\Requests\Curriculum\Subjects;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SubjectShow.
 *
 * @package App\Http\Requests
 */
class SubjectShow extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('subjects.show');
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
