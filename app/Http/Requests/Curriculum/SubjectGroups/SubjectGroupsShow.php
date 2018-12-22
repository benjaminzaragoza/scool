<?php

namespace App\Http\Requests\Curriculum\SubjectGroups;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SubjectGroupsShow.
 *
 * @package App\Http\Requests
 */
class SubjectGroupsShow extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('subjectGroups.show');
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
