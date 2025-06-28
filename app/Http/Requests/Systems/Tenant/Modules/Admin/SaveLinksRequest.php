<?php

namespace App\Http\Requests\Systems\Tenant\Modules\Admin;

use App\Libs\Utils;
use App\Libs\Enums\EnumLinkType;
use Illuminate\Foundation\Http\FormRequest;

class SaveLinksRequest extends FormRequest
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
         * Extraindo todos os dados do request
         */
        $dataRequest = $this->request->all();

        /**
         * Podemos está lidando em um modal, com isso devemos concatenar 'modal-link-' para garantir que não haja conflitos entre campos que não são
         * do modal com o campo do formulário padrão
         */

        $prefixName = isset($dataRequest["prefixNameLinks"]) ? $dataRequest["prefixNameLinks"] : "";
        list($fieldFile, $fieldStoresId, $fieldSlug, $fieldLink, $fieldLinkType, $fieldTitle) = Utils::addPrefix(
            $prefixName,
            ["file", "stores_id", "slug", "link", "link_type", "title"]
        );
        /**
         * retornos obrigatórios
         */
        $return[$fieldStoresId] = "required";
        $return[$fieldSlug] = "required|maxlength:255|unique:links,slug";
        $return[$fieldTitle] = "required|maxlength:200";

        /**
         * Extraindo todos os dados do request
         */
        $dataRequest = $this->request->all();

        /**
         * Verificar se o link é do tipo arquivo ou redirecionamento
         */
        if ($dataRequest[$fieldLinkType] == EnumLinkType::FILE) {
            $return[$fieldFile] =
                "required|mimes:jpg,png,ppt,pptx,doc,docx,pdf,xls,xlsx,zip|file|max:" . env("FILE_SIZE");
        } else {
            $return[$fieldLink] = "required";
        }

        return $return;
    }
    public function messages()
    {
        return [
            "required" => "Campo obrigatório.",
            "unique" => "Url de acesso já atribuída.",
            "mimes" => "Tipo de arquivo válido: :values.",
        ];
    }
}
