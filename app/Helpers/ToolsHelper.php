<?php

use App\Services\Crud\CrudLinkService;

if (!function_exists("toFloat")) {
    function toFloat($str)
    {
        if (strstr($str, ",")) {
            $str = str_replace(".", "", $str); // replace dots (thousand seps) with blancs
            $str = str_replace(",", ".", $str); // replace ',' with '.'
        }

        if (preg_match("#([0-9\.\-]+)#", $str, $match)) {
            // search for number that may contain '.'
            return floatval($match[0]);
        } else {
            return floatval($str); // take some last chances with floatval
        }
    }
}

if (!function_exists("objExistId")) {
    function objExistId($array, $atribute, $id)
    {
        foreach ($array as $obj) {
            if ($obj->$atribute == $id) {
                return true;
                break;
            }
        }
        return false;
    }
}

if (!function_exists("versionScripts")) {
    function versionScripts()
    {
        return "?" . config("app.version") . "." . config("app.compilation");
    }
}

if (!function_exists("MaskFields")) {
    function MaskFields($val, $mask)
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
}

if (!function_exists("MaskPhone")) {
    function MaskPhone($str)
    {
        if ($str != null) {
            switch (strlen($str)) {
                case 10:
                    $str = MaskFields($str, "(##) ####-####");
                    break;
                case 11:
                    $str = MaskFields($str, "(##) #####-####");
                    break;
            }
        }

        return $str;
    }
}

if (!function_exists("getLinksSlug")) {
    function getLinksSlug($linksId)
    {
        $path = trim(env("APP_URL"));
        return $path .
            ($path[strlen($path) - 1] == "/" ? "" : "/") .
            app(CrudLinkService::class)->findById($linksId)->slug;
    }
}
