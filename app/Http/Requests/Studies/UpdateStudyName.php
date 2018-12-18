<?php

namespace App\Http\Requests\Studies;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateStudyName.
 *
 * @package App\Http\Requests
 */
class UpdateStudyName extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('studies.update', $this->incident);
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
