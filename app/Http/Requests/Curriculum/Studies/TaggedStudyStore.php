<?php

namespace App\Http\Requests\Curriculum\Studies;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TaggedStudyStore.
 *
 * @package App\Http\Requests
 */
class TaggedStudyStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('tagged.studies.store',$this->study);
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
