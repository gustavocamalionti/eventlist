<?php

namespace App\Http\Requests\Systems\Tenant\Modules\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannersRequest extends FormRequest
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

        /**
         * retornos obrigatórios
         */
        $return["name"] = "required|maxlength:255";

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

        /**
         * Extraindo todos os arquivos do request
         */
        $arrayFiles = $this->file();

        /**
         * Se houve mudança nos banners, devemos aplicar as validações. Só é possível identificar um banner
         * alterado se houver algum novo arquivo enviado pelo request.
         */
        if ($arrayFiles > 0) {
            foreach ($arrayFiles as $key => $value) {
                switch ($key) {
                    case "file_desktop":
                        $return["file_desktop"] =
                            "required|mimes:png,jpg|max:" .
                            env("BANNER_SIZE_DESKTOP") .
                            "|dimensions:" .
                            "width=" .
                            env("BANNER_WIDTH_DESKTOP") .
                            ",height=" .
                            env("BANNER_HEIGHT_DESKTOP");
                        break;
                    case "file_mobile":
                        $return["file_mobile"] =
                            "required|mimes:png,jpg|max:" .
                            env("BANNER_MAX_SIZE_MOBILE") .
                            "|dimensions:" .
                            "width=" .
                            env("BANNER_WIDTH_MOBILE") .
                            ",height=" .
                            env("BANNER_HEIGHT_MOBILE");
                        break;
                }
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
