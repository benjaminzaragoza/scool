<?php

namespace App\Http\Requests\Curriculum\SubjectGroups;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SubjectGroupSubjectsNumberUpdate.
 *
 * @package App\Http\Requests
 */
class SubjectGroupSubjectsNumberUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('subjectGroups.update', $this->subjectGroup);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['subjects_number' => 'required'];
    }
}
