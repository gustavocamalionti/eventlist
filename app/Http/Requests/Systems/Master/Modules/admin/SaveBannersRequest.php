<?php

namespace App\Http\Requests\Systems\Master\Modules\admin;

use Illuminate\Foundation\Http\FormRequest;

class SaveBannersRequest extends FormRequest
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
        $return = [];

        $return["name"] = "required|maxlength:255";
        $return["file_desktop"] =
            "required|mimes:png,jpg|max:" .
            env("BANNER_SIZE_DESKTOP") .
            "|dimensions:" .
            "width=" .
            env("BANNER_WIDTH_DESKTOP") .
            ",height=" .
            env("BANNER_HEIGHT_DESKTOP");
        $return["file_mobile"] =
            "required|mimes:png,jpg|max:" .
            env("BANNER_MAX_SIZE_MOBILE") .
            "|dimensions:" .
            "width=" .
            env("BANNER_WIDTH_MOBILE") .
            ",height=" .
            env("BANNER_HEIGHT_MOBILE");

        /**
         * Extraindo todos os dados do request
         */
        $dataRequest = $this->request->all();

        /**
         * A opção de agendamento do banner foi selecionada
         *
         */

        if ($dataRequest["is_schedule"]) {
            //Ao menos uma data
            if (
                array_key_exists("date_start", $dataRequest) &&
                array_key_exists("date_end", $dataRequest) &&
                $dataRequest["date_start"] == null &&
                $dataRequest["date_end"] == null
            ) {
                $return["date_start"] = "required";
                $return["date_end"] = "required";
            }

            //Quando existir data final, ela precisa ser maior que a data inicial
            if ($dataRequest["date_end"] != null) {
                $return["date_end"] = "date_format:d/m/Y|after:date_start";
            }
        }

        return $return;
    }
    public function messages()
    {
        return [
            "required" => "Campo obrigatório",
            "maxlength" => "Limite de caracteres atingido (:maxlength).",
            "date_start.required" => "Defina pelo menos uma data de agendamento.",
            "date_end.required" => "Defina pelo menos uma data de agendamento.",
            "after" => "A data final precisa ser maior que a data inicial.",
            "mimes" => "Tipos de arquivos compatíveis: PNG ou JPG",
            "max" => "Tamanho de arquivo máximo atingido (:maxKB).",
            "dimensions" => "As dimensões precisam ter (:widthpx) de largura e (:heightpx) de altura.",
            "date_format" => "Formato do campo inválido!",
        ];
    }
}
