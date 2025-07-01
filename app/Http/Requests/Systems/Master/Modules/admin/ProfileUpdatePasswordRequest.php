<?php

namespace App\Http\Requests\Systems\Master\Modules\admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdatePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "password" => "required|confirmed|min:6|max:255",
            "password_confirmation" => "required|min:6|max:255",
        ];
    }
}
