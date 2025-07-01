<?php

namespace App\Http\Requests\Systems\Master\Modules\admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveFormConfigsRequest extends FormRequest
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
        return [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:stores,email",
            "forms_id" => "required",
            "form_subjects_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            "required" => "Campo obrigatório.",
            "maxlength" => "Você ultrapassou o limite de :maxlength caracteres.",
            "email" => "Não é um email válido",
            "string" => "Não é um texto válido",
            "max" => "Quantidade de :max caracteres excedida!",
        ];
    }
}
