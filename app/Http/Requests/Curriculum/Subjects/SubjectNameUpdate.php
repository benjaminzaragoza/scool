<?php

namespace App\Http\Requests\Curriculum\Subjects;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SubjectNameUpdate.
 *
 * @package App\Http\Requests
 */
class SubjectNameUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('subjects.update', $this->subject);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['name' => 'required'];
    }
}
