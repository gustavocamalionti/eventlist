<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class ConfigParametersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
        $rules = [
            "page_title" => "required",
            "facebook_link" => "required",
            "instagram_link" => "required",
            "official_site" => "required",
            "apicep" => "required",
        ];

        $isTenant = tenancy()->initialized;

        if ($isTenant) {
        }

        return $rules;
    }
}
