<?php

namespace App\Libs;

use App\Libs\Enums\EnumLinksSlug;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * Created by PhpStorm.
 * User: Marcelo
 * Date: 08/05/17
 * Time: 20:32
 */
class Utils
{
    public static function ClearMask($valor)
    {
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
    }

    public static function MaskFields($val, $mask)
    {
        /*
         * Exemplos de uso
         *
         * $cpf = "00100200300";
         * $cep = "08665110";
         * $data = "10102010";
         *
         * echo MaskFields($cnpj,'##.###.###/####-##');
         * echo MaskFields($cpf,'###.###.###-##');
         * echo MaskFields($cep,'#####-###');
         * echo MaskFields($data,'##/##/####');
         *
         */

        $maskared = "";
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == "#") {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        return $maskared;
    }

    public static function array_orderby()
    {
        $args = func_get_args();
        $data = array_shift($args);

        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = [];
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;

        call_user_func_array("array_multisort", $args);
        return array_pop($args);
    }

    public static function MaskPhone($n)
    {
        $tam = strlen(preg_replace("/[^0-9]/", "", $n));

        if ($tam == 13) {
            // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
            return "+" .
                substr($n, 0, $tam - 11) .
                " (" .
                substr($n, $tam - 11, 2) .
                ") " .
                substr($n, $tam - 9, 5) .
                "-" .
                substr($n, -4);
        }
        if ($tam == 12) {
            // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
            return "+" .
                substr($n, 0, $tam - 10) .
                " (" .
                substr($n, $tam - 10, 2) .
                ") " .
                substr($n, $tam - 8, 4) .
                "-" .
                substr($n, -4);
        }
        if ($tam == 11) {
            // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
            return " (" . substr($n, 0, 2) . ") " . substr($n, 2, 5) . "-" . substr($n, 7, 11);
        }
        if ($tam == 10) {
            // COM CÓDIGO DE ÁREA NACIONAL
            return " (" . substr($n, 0, 2) . ") " . substr($n, 2, 4) . "-" . substr($n, 6, 10);
        }
        if ($tam <= 9) {
            // SEM CÓDIGO DE ÁREA
            return substr($n, 0, $tam - 4) . "-" . substr($n, -4);
        }
    }

    public static function addPrefix($prefix, $fields)
    {
        return array_map(fn($field) => ($prefix ?? "") . $field, $fields);
    }

    public static function getAllKeysColumns($data)
    {
        return array_keys(get_object_vars($data[0]));
    }

    public static function maxOptimizations()
    {
        try {
            // Definir as diretivas para upload de arquivos grandes
            ini_set("upload_max_filesize", "10G");
            ini_set("post_max_size", "10G");
            ini_set("max_input_time", "3600"); // Tempo máximo de input (1 hora)
            ini_set("max_execution_time", "3600"); // Tempo máximo de execução (1 hora)
            ini_set("memory_limit", "-1"); // Sem limite de memória

            return true;
        } catch (\Throwable $th) {
            // Lançar exceção em caso de falha
            throw new \Exception("Erro ao configurar limites de upload: " . $th->getMessage(), 1);
        }
    }

    public static function generateThumbnail($path, $thumbHeight)
    {
        // Caminho da imagem original
        $originalImagePath = storage_path($path);

        // Verifica se o arquivo existe
        if (!file_exists($originalImagePath)) {
            return response()->json(["error" => "Arquivo não encontrado."], 404);
        }

        // Obtém as informações do arquivo
        $imageInfo = getimagesize($originalImagePath);
        if ($imageInfo === false) {
            return response()->json(["error" => "Não foi possível obter informações sobre a imagem."], 400);
        }

        list($width, $height) = $imageInfo;
        $mimeType = $imageInfo["mime"];

        // Cria a imagem original a partir do arquivo com base no tipo MIME
        switch ($mimeType) {
            case "image/jpeg":
                $sourceImage = imagecreatefromjpeg($originalImagePath);
                break;
            case "image/png":
                $sourceImage = imagecreatefrompng($originalImagePath);
                break;
            case "image/gif":
                $sourceImage = imagecreatefromgif($originalImagePath);
                break;
            default:
                return response()->json(["error" => "Formato de imagem não suportado."], 400);
        }

        // Calcula a nova largura mantendo a proporção
        $newHeight = $thumbHeight;
        $newWidth = floor($width * ($thumbHeight / $height));

        // Cria a imagem em branco para a thumbnail
        $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

        // Redimensiona a imagem original para a thumbnail
        imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Caminho para salvar a thumbnail
        $extension = "." . pathinfo($path, PATHINFO_EXTENSION);

        $thumbnailPath = storage_path("app/uploads/" . basename($path, $extension) . "_thumbnail" . $extension);

        // Salva a thumbnail no formato apropriado
        switch ($mimeType) {
            case "image/jpeg":
                imagejpeg($thumbnail, $thumbnailPath, 90); // Qualidade 70
                break;
            case "image/png":
                imagepng($thumbnail, $thumbnailPath);
                break;
            case "image/gif":
                imagegif($thumbnail, $thumbnailPath);
                break;
        }

        // Libera a memória
        imagedestroy($thumbnail);
        imagedestroy($sourceImage);

        return [
            "name" => basename($path, $extension) . "_thumbnail" . $extension,
            "path" => "uploads/" . basename($path, $extension) . "_thumbnail" . $extension,
        ];
    }

    public static function setConnections()
    {
        try {
            DB::purge("mysql_dinamic");
            Config::set([
                "database.connections.mysql_dinamic" => session("mysql_dinamic"),
            ]);
            DB::reconnect("mysql_dinamic");

            return true;
        } catch (\Throwable $th) {
            throw new Exception("Falha na conexão com banco de dados", 1);
        }
    }

    public static function createConnections($host, $port, $user, $password, $database)
    {
        try {
            $connection = config("database.connections.mysql_dinamic");
            $connection["host"] = $host;
            $connection["port"] = $port;
            $connection["username"] = $user;
            $connection["password"] = $password;
            $connection["database"] = $database;

            session_unset();

            session(["mysql_dinamic" => $connection]);

            return session("mysql_dinamic");
        } catch (\Throwable $th) {
            throw new Exception("Falha na sessão.", 1);
        }
    }

    public static function sumColumnArray($array, $column)
    {
        $value = 0;
        foreach ($array as $item) {
            $value += floatval($item[$column]);
        }

        return $value;
    }
}
