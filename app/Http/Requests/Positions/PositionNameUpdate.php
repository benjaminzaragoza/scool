<?php

namespace App\Http\Requests\Positions;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PositionNameUpdate.
 *
 * @package App\Http\Requests
 */
class PositionNameUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('positions.update', $this->study);
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
