<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;

class SaveUsersRequest extends FormRequest
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
        $validations = [
            "cpf" => "required|cpf|unique:users,cpf",
            "email" => "required|email|max:255|unique:users,email",
            "name" => "required|string|max:255",
            "date_birth" => "required",
            "zipcode" => "required",
            "number" => "required",
            "phone_cell" => "required",
            "address" => "required|string|max:255",
            "district" => "required|string|max:255",
            "complement" => "max:255",
            "cities_id" => "required",
            "roles_id" => "required",
            "states_id" => "required",
            "password" => "required|confirmed",
            "password_confirmation" => "required",
        ];

        if (!array_key_exists("news_accept", $this->request->all())) {
            $this->request->add(["news_accept" => "0"]);
        } else {
            $this->request->add(["news_accept" => "1"]);
        }

        if (!array_key_exists("permission_accept", $this->request->all())) {
            $this->request->add(["permission_accept" => "0"]);
        } else {
            $this->request->add(["permission_accept" => "1"]);
        }

        return $validations;
    }

    public function messages()
    {
        return [
            "email.exists" => "Não existe esse registro em nosso banco, entra em contato com um dos administradores.",
            "email.required" => "Por favor informe seu e-mail.",
            "email.email" => "Por favor informe um e-mail válido.",
            "password.required" => "Por favor informe sua senha.",
            "accepted" => "O campo deve ser marcado.",
        ];
    }
}
