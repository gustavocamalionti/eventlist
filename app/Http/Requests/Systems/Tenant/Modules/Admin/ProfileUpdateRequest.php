<?php

namespace App\Http\Requests\Systems\Tenant\Modules\Admin;

use Illuminate\Validation\Rule;
use App\Models\Systems\Tenant\TenantUser;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $validations = [
            // "cpf" => "required|cpf|unique:users,cpf," . $this->id,
            "name" => "required|string|max:255",
            // "date_birth" => "required",
            // "zipcode" => "required",
            // "number" => "required",
            // "phone_cell" => "required",
            "address" => "nullable|string|max:255",
            "district" => "nullable|string|max:255",
            "complement" => "nullable|string|max:255",
            // "cities_id" => "required",
            // "states_id" => "required",
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


        if (array_key_exists("cpf", $this->request->all()) && $this->request->all()['cpf'] != null) {
            $validations["cpf"] =  ["cpf", Rule::unique(TenantUser::class)->ignore($this->user()->cpf)];
        }

        if (array_key_exists("email", $this->request->all())) {
            $validations["email"] = ["required",  Rule::unique(TenantUser::class)->ignore($this->user()->id)];
        }

        return $validations;
    }

    public function messages()
    {
        return [
            "accepted" => "O campo deve ser marcado.",
            "required" => "Campo obrigatório",
            "cpf" => "CPF inválido",
        ];
    }
}
