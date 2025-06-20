<?php

namespace App\Http\Requests\Panel;

use App\Models\User;
use App\Libs\Enums\EnumLinkType;
use App\Services\Crud\CrudLinkService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLinksRequest extends FormRequest
{
    protected $crudLinkService;
    public function __construct(CrudLinkService $crudLinkService)
    {
        $this->crudLinkService = $crudLinkService;
    }
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
         * retornos obrigatórios
         */
        $isFixed = $this->crudLinkService->findById($this->id)->is_fixed;
        $linkType = "";
        if (!$isFixed) {
            $return["stores_id"] = "required";
            $return["slug"] = "required|maxlength:255|unique:links,slug," . $this->id;
            $return["title"] = "required|maxlength:200";
            $linkType = $dataRequest["link_type"];
        } else {
            $linkType = $this->crudLinkService->findById($this->id)->link_type;
        }

        /**
         * Extraindo todos os arquivos do request
         */
        $arrayFiles = $this->file();

        /**
         * Verificar se o link é do tipo arquivo ou redirecionamento
         */
        if ($linkType == EnumLinkType::FILE) {
            //Verificar se há alteração de arquivo. Se sim, aplicar a regra de validação
            $return["name"] = "required|maxlength:255";
            if (count($arrayFiles) > 0) {
                $return["file"] =
                    "required|mimes:jpg,png,ppt,pptx,doc,docx,pdf,xls,xlsx,zip|file|max:" . env("FILE_SIZE");
            }
        } else {
            $return["link"] = "required";
        }

        return $return;
    }
    public function messages()
    {
        return [
            "required" => "Campo obrigatório.",
            "slug.unique" => "Url de acesso já atribuída.",
            "file.mimes" => "Tipo de arquivo válido: :values.",
        ];
    }
}
