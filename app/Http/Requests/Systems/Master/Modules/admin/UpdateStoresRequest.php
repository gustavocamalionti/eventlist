<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoresRequest extends FormRequest
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
            "cnpj" => "required|cnpj|unique:stores,cnpj," . $this->id,
            "email" => "required|email|max:255|unique:stores,email," . $this->id,
            "name" => "required|string|max:255",
            "zipcode" => "required",
            "number" => "required",
            "phone1" => "required",
            "address" => "required|string|max:255",
            "district" => "required|string|max:255",
            "complement" => "max:255",
            "cities_id" => "required",
            "states_id" => "required",
            "brands_id" => "required",
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
