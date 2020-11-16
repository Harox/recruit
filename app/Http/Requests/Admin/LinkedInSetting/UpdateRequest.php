<?php

namespace App\Http\Requests\Admin\LinkedInSetting;

use App\Package;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (!$this->has('status')) {
            $this->request->add(['status' => 'disable']);
        }

        return [
            'client_id' => 'required_if:status,enable',
            'client_secret' => 'required_if:status,enable',
            'callback_url' => 'required_if:status,enable',
        ];
    }
}
