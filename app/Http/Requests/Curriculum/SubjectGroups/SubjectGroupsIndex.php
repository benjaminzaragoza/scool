<?php

namespace App\Http\Requests\Curriculum\SubjectGroups;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SubjectGroupsIndex.
 *
 * @package App\Http\Requests
 */
class SubjectGroupsIndex extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('subjectGroups.index');
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
