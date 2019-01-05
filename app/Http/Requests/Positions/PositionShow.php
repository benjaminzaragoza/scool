<?php

namespace App\Http\Requests\Positions;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PositionShow.
 *
 * @package App\Http\Requests
 */
class PositionShow extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('positions.show');
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
