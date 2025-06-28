<?php

namespace App\Services\Common\Rules;

use Illuminate\Support\Str;
use App\Services\BaseRulesService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RulesFilesService extends BaseRulesService
{
    /**
     * Deletar um arquivo do Storage
     */

    public function fileDelete($path)
    {
        return Storage::delete($path);
    }

    /**
     * Ao alterar algum arquivo, é preciso excluir o antigo
     */
    public function deleteOldFile($fileOld, $fileNew, $path)
    {
        if (!is_null($fileOld) and !is_null($fileNew) && $fileOld != $fileNew) {
            $lastIndex = strlen($path) - 1;

            if ($lastIndex == "/") {
                return $this->fileDelete($path . $fileOld);
            }

            $this->fileDelete($path . "/" . $fileOld);
        }
    }

    /**
     * Gerar um nome para o arquivo codificado
     */
    public function generateFileNameUUID($file)
    {
        if (!is_null($file)) {
            $file_extension = "." . $file->getClientOriginalExtension();
            $uuid = Str::uuid();
            return $uuid . $file_extension;
        }
        return null;
    }

    /**
     * Salvar o arquivo na pasta Storage
     */
    public function saveFileStorage($fileRequest, $path, $fileName)
    {
        $fileRequest->storeAs($path, $fileName);
    }

    /**
     * Gera o nome do arquivo e salva em Storage
     */
    public function generateNameAndSaveFile($fileRequest, $path, $oldFile = null)
    {
        if ($fileRequest != "undefined" && !is_null($fileRequest)) {
            $nameFile = $this->generateFileNameUUID($fileRequest);
            $this->saveFileStorage($fileRequest, $path, $nameFile);
            return $nameFile;
        }

        return $oldFile;
    }

    /**
     * Ao atualizar, devemos definir o nome do arquivo final
     */

    public function defineNameUpdate($imageOld, $imageNew)
    {
        return $imageNew !== null ? $imageNew : $imageOld;
    }

    /**
     * Muitas vezes é necessário criar apenas a pasta
     */

    public function createFolder($path = "storage/public")
    {
        $response = Storage::directoryExists($path);

        if ($response == false) {
            return Storage::createDirectory($path);
        }
        return false;
    }

    public function renameOrMoveFile($pathOld, $pathNew)
    {
        return Storage::move($pathOld, $pathNew);
    }
}
