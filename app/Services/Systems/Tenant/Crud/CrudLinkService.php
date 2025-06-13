<?php

namespace App\Services\Crud;

use App\Libs\Enums\EnumLinkType;
use App\Repositories\LinkRepository;
use App\Services\Bases\BaseCrudService;
use App\Services\Panel\Rules\RulesFilesService;

class CrudLinkService extends BaseCrudService
{
    protected $repository;
    protected $rulesFilesService;

    public function __construct(
        LinkRepository $repository,

        RulesFilesService $rulesFilesService
    ) {
        $this->repository = $repository;
        $this->rulesFilesService = $rulesFilesService;
    }

    /**
     * o campo name só recebe o nome do arquivo salvo em storage. Se não for um link do tipo file, ele precisa ser retornado vazio.
     */
    public function saveLinkType($data)
    {
        if ($data["link_type"] == EnumLinkType::FILE) {
            $name = $this->rulesFilesService->generateNameAndSaveFile(
                $data["file"],
                "public/site/files/",
                $data["name"]
            );
            return $name;
        }
        return "";
    }

    /**
     * o campo name só recebe o nome do arquivo salvo em storage. Se não for um link do tipo file, ele precisa ser retornado vazio.
     */
    public function updateLinkType($request)
    {
        //Verifica se estamos querendo atualizar o arquivo o ulink
        if ($request->link_type == EnumLinkType::FILE) {
            $name = $this->rulesFilesService->generateNameAndSaveFile(
                $request->file,
                "public/site/files/",
                $request->name
            );
            return $name;
        }

        //Se estivermos atualizando link e o tipo antigo era arquivo, precisamos deletar ele do sistema e redefinir a variável nameFile.
        if ($request->name != null) {
            $this->rulesFilesService->fileDelete("public/site/files/" . $request->name);
            return "";
        }
    }

    /**
     * Só iremos deletar o file se estivermos usando com o link_type = file, caso contrário não acontece nada.
     */
    public function deleteLinkType($request, $url)
    {
        if ($request->link_type == EnumLinkType::FILE && $request->file != "undefined") {
            $this->rulesFilesService->fileDelete($url . "/" . $request->name);
        }
    }
}
