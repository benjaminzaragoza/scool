<?php

namespace App\Http\Requests;

use App\Models\Job;
use Auth;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ShowJobsManagement.
 *
 * @package App\Http\Requests
 */
class ShowJobsManagement extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('show-jobs',Job::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
