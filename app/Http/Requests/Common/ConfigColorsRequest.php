<?php

namespace App\Http\Requests\Systems\Master\Modules\admin;

use Illuminate\Foundation\Http\FormRequest;

class ConfigColorsRequest extends FormRequest
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
            "facebook_title" => "required",
            "facebook_link" => "required",
            "instagram_title" => "required",
            "instagram_link" => "required",
            "official_site" => "required",
            "apicep" => "required",
            "extranet_site" => "required",
            "ifood_site" => "required",
        ];

        $isTenant = tenancy()->initialized;

        if ($isTenant) {
        }

        return $rules;
    }
}
