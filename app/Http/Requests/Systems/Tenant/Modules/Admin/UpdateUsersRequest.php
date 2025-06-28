<?php

namespace App\Http\Requests\Systems\Tenant\Modules\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsersRequest extends FormRequest
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
            // "cpf" => "required|cpf|unique:users,cpf," . $this->id,
            "email" => "required|email|max:255|unique:users,email," . $this->id,
            "name" => "required|string|max:255",
            // "date_birth" => "required",
            // "zipcode" => "required",
            // "number" => "required",
            // "phone_cell" => "required",
            // "address" => "required|string|max:255",
            // "district" => "required|string|max:255",
            // "complement" => "max:255",
            // "cities_id" => "required",
            // "states_id" => "required",
            "roles_id" => "required",
            "permission_accept" => "required|accepted",
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

        if ($this->request->all()["password"] != null) {
            $validations["password"] = "required|confirmed";
            $validations["password_confirmation"] = "required";
        } else {
            $this->request->remove("password");
            $this->request->remove("password_confirmation");
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
