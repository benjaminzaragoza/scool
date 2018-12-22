<?php

namespace App\Http\Requests\Curriculum\SubjectGroups;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SubjectGroupsDestroy.
 *
 * @package App\Http\Requests
 */
class SubjectGroupsDestroy extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('subjectGroups.destroy');
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
