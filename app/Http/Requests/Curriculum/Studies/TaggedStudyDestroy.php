<?php

namespace App\Http\Requests\Studies;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TaggedStudyDestroy.
 *
 * @package App\Http\Requests
 */
class TaggedStudyDestroy extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('tagged.studies.destroy',$this->incident);
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
