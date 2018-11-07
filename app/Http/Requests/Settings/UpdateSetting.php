<?php

namespace App\Http\Requests\Settings;

use App\Models\Setting;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateSetting
 * @package App\Http\Requests
 */
class UpdateSetting extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('setting.update',$this->setting);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value'     => 'required'
        ];
    }
}
