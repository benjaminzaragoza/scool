<?php

namespace App\Http\Requests\Curriculum\SubjectGroups;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SubjectGroupsStore.
 *
 * @package App\Http\Requests
 */
class SubjectGroupsStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('subjectGroups.store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'shortname' => 'required',
            'code' => 'required|unique:subject_groups,code'
        ];
    }
}
