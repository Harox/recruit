<?php

namespace App\Http\Requests\Company;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompany extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('manage_settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name' => 'required',
            'company_email' => 'required'
        ];
    }
}
