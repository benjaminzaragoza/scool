<?php

namespace App\Http\Requests\Curriculum\Subjects;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SubjectDestroy.
 *
 * @package App\Http\Requests
 */
class SubjectDestroy extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('subjects.destroy');
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
